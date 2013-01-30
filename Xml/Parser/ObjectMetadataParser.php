<?php
namespace Rhapsody\SetupBundle\Xml\Parser;

use Rhapsody\SetupBundle\Model\ObjectMetadata;
use Rhapsody\SetupBundle\Xml\XmlElement;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Xml\Parser
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class ObjectMetadataParser extends ObjectParser
{

	/**
	 *
	 */
	public static function parseMetadata($xml)
	{
		$parser = new ObjectMetadataParser();
		return $parser->parse($xml);
	}

	/**
	 * Ammends the assertions for object metadata. An object should not have
	 * both <tt>parent</tt> and <tt>ref</tt> attributes set.
	 * @param XmlElement $xml
	 * @throws \InvalidArgumentException if both the <tt>parent</tt> and
			<tt>ref</tt> attributes are set.
	 * @see \Rhapsody\SetupBundle\Xml\Parser\ObjectMetadataParser::assert()
	 */
	public function assert($xml)
	{
		parent::assert($xml);

		$parent = $xml->getAttribute('parent');
		$ref = $xml->getAttribute('ref');
		if (!empty($parent) && !empty($ref)) {
			throw new \InvalidArgumentException('Found "parent" and "ref" attributes both defined on the same XML object. Please use one or the other. XML: '.strval($xml));
		}
	}

	/**
	 * @return \Rhapsody\SetupBundle\Model\ObjectMetadata
	 * 		the metadata representing this object.
	 */
	public function parse($xml)
	{
		$this->assert($xml);
		$this->parseAttributes($xml);

		$metadata = new ObjectMetadata();
		$this->applyAttributes($metadata);
		return $metadata;
	}

	public function parseAttributes($xml)
	{
		$this->attributes = $xml->getAttributes();
		foreach ($this->attributes as $name => $value)
		{
			$key = strtolower(trim($name));
			$this->attributesMap[$key] = $name;
		}
		return $this->attributes;
	}

	/**
	 *
	 * @param \Rhapsody\SetupBundle\Model\ObjectMetadata $metadata
	 */
	public function applyAttributes(&$metadata)
	{
		$name = $this->getAttribute('name');
		if (!empty($name)) {
			$this->getLog()->debug('Apply name attribute with value: '.$name);
			$metadata->setName($name);
		}

		$class = $this->getAttribute('class');
		if (!empty($class)) {
			$this->getLog()->debug('Apply class attribute with value: '.$class);
			$metadata->setClassName($class);
		}

		$parent = $this->getAttribute('parent');
		if (!empty($parent)) {
			$this->getLog()->debug('Apply parent attribute with value: '.$parent);
			$metadata->setParentRef($parent);
		}

		$ref = $this->getAttribute('ref');
		if (!empty($ref)) {
			$this->getLog()->debug('Apply ref attribute with value: '.$ref);
			$metadata->setRef($ref);
		}

		$abstract = $this->getBooleanAttribute('abstract');
		$this->getLog()->debug('Apply abstract attribute with value: '.($abstract ? 'true' : 'false').' type('.gettype($abstract).')');
		$metadata->setAbstract($abstract);

		$transient = $this->getBooleanAttribute('transient');
		$this->getLog()->debug('Apply transient attribute with value: '.($transient ? 'true': 'false'));
		$metadata->setTransient($transient);
	}
}