<?php
/* Copyright (c) 2013 Rhapsody Project
 *
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Rhapsody\SetupBundle\Xml;

use Rhapsody\SetupBundle\Converter\ConverterFactory;
use Rhapsody\SetupBundle\Model\Object;
use Rhapsody\SetupBundle\Model\ObjectMetadata;
use Rhapsody\SetupBundle\Model\Property;
use Rhapsody\SetupBundle\Model\PropertyMetadata;
use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Model\DataSource\FileDataSource;
use Rhapsody\SetupBundle\Populator\IPopulator;
use Rhapsody\SetupBundle\Xml\XmlElement;
use Rhapsody\SetupBundle\Xml\Parser\ObjectMetadataParser;
use Rhapsody\SetupBundle\Xml\Parser\PropertyMetadataParser;
use Rhapsody\SetupBundle\Xml\Parser\QueryParser;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Xml
 * @copyright Copyright (c) 2013 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class XmlDataSource extends FileDataSource
{

	/**
	 * The parsed <tt>XML</tt>.
	 * @var XmlElement
	 */
	private $xml;

	/**
	 * Any errors that occur when loading the XML file are moved into this
	 * error string.
	 */
	private $_loadFileErrorStr = null;

	/**
	 *
	 * @param string $file
	 * @param IPopulator $populator
	 * @throws \Exception
	 */
	public function __construct($file = null)
	{
		parent::__construct($file);
		$this->xml = null;
	}

	public function getXml()
	{
		return $this->xml;
	}

	/**
	 *
	 */
	public function prepare()
	{
		if ($this->xml !== null) {
			return;
		}
		$this->xml = $this->parse();

		$objects = $this->xml->getAll('object');
		$this->getLog()->debug('Found '.count($objects).' objects in file.');

		// ** Catalogue every object that has an id attribute.
		$objects = $this->xml->getAll('object[@name]');
		$this->getLog()->debug('Found '.count($objects).' objects to index.');
		foreach ($objects as $object) {
			$attr = $object->getAttribute('name');
			$name = strtolower(trim($attr));
			if (in_array($name, $this->index)) {
				throw new \Exception('Duplicate objects detected in file: '.$this->file.' with ID: '.$name);
			}
			array_push($this->index, $name);
		}
		$this->prepared = true;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Data\IDataSource::findObject()
	 */
	public function findObject($name)
	{
		$results = $this->xml->getAll("object[@name = '".$name."']");
		if (is_array($results)) {
			if (count($results) >= 1) {
				return $this->parseObject($results[0]);
			}
		}
		return null;
	}

	/**
	 * <p>
	 * Gets all of the root-level <tt>&lt;object></tt> elements and parses them.
	 * </p>
	 */
	protected function handle()
	{
		$queries = $this->xml->get('query', false);
		$this->getLog()->debug('Processing queries. Found: '.count($queries).' queries in: '.$this->file);
		foreach ($queries as $queryElement) {
			$query = QueryParser::parseQuery($queryElement);
			$this->query($query);
		}

		$objects = $this->xml->get('object', false);
		$this->getLog()->debug('Processing '.count($objects).' objects in: '.$this->file);
		foreach ($objects as $element) {
			$dataObject = $this->parseObject($element);
			$this->populator->save();
		}
	}

	/**
	 * <p>
	 * Reads an <tt>&lt;array></tt> element from the <tt>XML</tt> and parses it
	 * into an <tt>array</tt> data type.
	 * </p>
	 *
	 * @param XmlElement $element the <tt>XML</tt> element.
	 * @return array the <tt>array</tt> represented by the element.
	 */
	public function parseArray($element)
	{
		$arr = array();
		$children = $element->get('value', false);
		foreach ($children as $child) {
			$value = $this->getValue($child);
			$key = $child->getAttribute('key');
			if (!empty($key)) {
				$key = is_numeric($key) ? $key + 0 : strval(trim($key));
				$arr[$key] = $value;
			}
			else {
				array_push($arr, $value);
			}
		}
		return $arr;
	}

	/**
	 * <p>
	 * Reads and returns a <tt>DataObject</tt> representing the object to be
	 * cached saved to the database. If the object has already been read into
	 * the cache, the cached object will be returned instead.
	 * </p>
	 * <p>
	 * If the object is a reference object (i.e. <tt>&lt;object ref="{object-id}" /></tt>)
	 * then we attempt to resolve the object reference and return that as the
	 * read <tt>DataObject</tt>. Otherwise, the <tt>&lt;object></tt> element is
	 * parsed. If a reference cannot be obtained within the current
	 * <tt>DataSource</tt>, the search will be extended to other data sources
	 * associated with the attributed <tt>$populator</tt>.
	 * </p>
	 * <p>
	 * When parsing an <tt>&lt;object></tt> element we read all of the
	 * <tt>&lt;property></tt> elements and parse their values, assigning them
	 * to the <tt>DataObject</tt>. Once
	 * </p>
	 *
	 * @param XmlElement $element
	 * @return \Rhapsody\SetupBundle\Data\DataObject the <tt>DataObject</tt>.
	 */
	public function parseObject($element, $persist = true)
	{
		// ** Assert that the $element is an instance of PHP's SimpleXMLElement...
		if (!($element instanceof \SimpleXMLElement)) {
			throw new \InvalidArgumentException('Read object expects a valid <object> XML element. Given: '.gettype($element));
		}

		$metadata = ObjectMetadataParser::parseMetadata($element);

		// ** Get the object's ID, and check the cache to see if it is in there...
		$name = $metadata->getName();
		if ($this->has($name)) {
			return $this->get($name);
		}

		// ** Is this an object reference? If so, we need to resolve the reference...
		$ref = $metadata->getRef();
		if (!empty($ref)) {
			return $this->getReference($ref);
		}

		// ** Create a new object with the metadata...
		$object = new Object($metadata);

		// ** If the object has a parent specified, inherit the properties and values from the parent...
		if ($object->hasParent()) {
			$parent = $this->getReference($object->getParentRef());
			$this->getLog()->debug('The object: '.$object.' has a parent object specified. Inheriting properties from parent: '.$parent);
			$object->inherit($parent);
		}

		// ** Parse all of the properties defined in the XML and set them on the object...
		$properties = $this->parseProperties($element);
		if ($properties !== null) {
			foreach ($properties as $property) {
				$object->addProperty($property);
				$object->set($property);
			}
		}

		// ** If the object has a name, cache it for future reference...
		if (!empty($name)) {
			$this->cache($name, $object);
		}

		// ** If the object is persistable, and it doesn't already have an object ID, queue it for persistence...
		if ($object->isPersistable() && $persist === true) {
			$id = $object->getId();
			if (empty($id)) {
				$this->queue($object);
			}
		}
		return $object;
	}

	/**
	 * <p>
	 * Parse all of the <tt>&lt;property></tt> elements from a the specified
	 * <tt>XML</tt> <tt>$element</tt>, and return an array of
	 * {@link Rhapsody\SetupBundle\Model\Property} objects.
	 * </p>
	 *
	 * @param XmlElement $element the XML element fro which to parse properties.
	 * @return array an array of <code>Property</code> objects.
	 */
	public function parseProperties(XmlElement $element)
	{
		$properties = array();
		$elements = $element->get('property', false);
		foreach ($elements as $prop)
		{
			$property = $this->parseProperty($prop);
			array_push($properties, $property);
		}
		return $properties;
	}

	/**
	 * <p>
	 * Reads the value of a <tt>&lt;property></tt> element. If the property is
	 * a reference property (e.g. <tt>&lt;property name="{name}" ref="{object-id}" /></tt>)
	 * then the object instance of the referenced object will be returned as the
	 * property's value. Otherwise the value of the property will be parsed and
	 * returned.
	 * </p>
	 * <p>
	 * A property may contain an object reference while not explicitly being
	 * identified as a <i>reference property</i>. In this event, the evaluation
	 * of the <tt>getValue()</tt> method will return the object instance.
	 * </p>
	 *
	 * @param XmlElement $element
	 * @return Property the property.
	 */
	public function parseProperty(XmlElement $element)
	{
		// ** Assert that the $element is an instance of PHP's SimpleXMLElement...
		if (!($element instanceof \SimpleXMLElement)) {
			throw new \InvalidArgumentException('Parse property expects a valid <property> XML element. Given: '.gettype($element));
		}

		$metadata = PropertyMetadataParser::parseMetadata($element);

		// **
		// If this property is an object-reference property, e.g. it is in the
		// form: <property name="{name}" ref="{object-id}" /> then we should
		// try to reconcile the object reference and return that as the value
		// [SWQ]
		$ref = $metadata->getRef();
		if (!empty($ref)) {
			$value = $this->getReference($ref);
			$instance = $value->getInstance();
			if ($metadata->hasField()) {
				$field = $metadata->getField();
				$property = new Property($metadata, $field->resolve($instance));
				return $property;
			}
			$property = new Property($metadata, $value->getInstance());
			return $property;
		}

		$statement = $metadata->getQuery();
		if (!empty($statement)) {
			$type = $metadata->getType();
			if (empty($type)) {
				throw new \InvalidArgumentException('Error parsing query property: query properties require a type to be specified.');
			}
			$class = $metadata->getTypeAsClassName();
			$projection = $metadata->getProjection();

			$_query = new Query($class, $statement, $projection);
			$value = $this->query($query);
			$property = new Property($metadata, $value);
			return $property;
		}

		$value = $this->getValue($element, $metadata->getType());
		$property = new Property($metadata, $value);
		return $property;
	}

	/**
	 * <p>
	 * Parses the value of property element.
	 * </p>
	 *
	 * @param XmlElement $property
	 * @param string $type the type of the value.
	 * @return mixed
	 */
	public function getValue($element, $type = null)
	{
		// ** Retrieve the value of the property element, in this case, it's an attribute.
		$attr = $element->getAttribute('value');
		if (!empty($attr)) {
			$attrs = $element->getAttributes();
			return ConverterFactory::getInstance()->convert($attr, $type, $attrs);
		}

		// ** Attempts to retrieve the value, based on the first child (if there is one)...
		$firstChild = $element->getFirstChild();
		if ($firstChild != null) {
			$name = $firstChild->getName();
			if ($name == 'array') {
				return $this->parseArray($firstChild);
			}
			if ($name == 'object') {
				$object = $this->parseObject($firstChild, false);
				return $object !== null ? $object->getInstance() : null;
			}
		}

		// ** Finally attempts to retrieve the value based on the text node.
		$text = $element->getValue();
		if (!empty($text)) {
			$attrs = $element->getAttributes();
			return ConverterFactory::getInstance()->convert($text, $type, $attrs);
		}
		return null;
	}

	/**
	 * @param string $xml the <tt>XML</tt> of <tt>.xml</tt> file that will be
	 *    parsed into <code>XmlElement</code>s.
	 * @return XmlElement
	 */
	protected function parse()
	{
		$this->getLog()->notice('Parsing data source XML from: '.$this->file);
		$xml = null;

		// **
		// Suppress warnings and errors while we load the XML, moving them rather
		// into a string that we can reference later.
		set_error_handler(array($this, '_loadFileErrorHandler'));
		$xml = simplexml_load_file($this->file, 'Rhapsody\SetupBundle\Xml\XmlElement');
		restore_error_handler();

		// Check if there was a error while loading file
		if ($this->_loadFileErrorStr !== null) {
			throw new \Exception($this->_loadFileErrorStr);
		}

		return $xml;
	}

	/**
	 * Handle any errors from simplexml_load_file or parse_ini_file
	 *
	 * @param integer $errno
	 * @param string $errstr
	 * @param string $errfile
	 * @param integer $errline
	 */
	protected function _loadFileErrorHandler($errno, $errstr, $errfile, $errline) {
		if ($this->_loadFileErrorStr === null) {
			$this->_loadFileErrorStr = $errstr;
		} else {
			$this->_loadFileErrorStr .= (PHP_EOL . $errstr);
		}
	}
}