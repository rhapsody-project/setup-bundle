<?php
namespace Rhapsody\SetupBundle\Xml\Parser;

use Rhapsody\SetupBundle\Parser\ObjectParser as BaseObjectParser;
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
class ObjectParser extends BaseObjectParser
{

	/**
	 * The array of elements that may be parsed by this parser.
	 * @var array
	 * @access protected
	 */
	protected $elements = array('object');

	/**
	 * Asserts that the supplied <tt>XML</tt> represents a valid
	 * <tt>*lt;object></tt>.
	 * @param XmlElement $xml
	 * @throws \NullPointerException if the passed <tt>$xml</tt> is null.
	 * @throws \InvalidArgumentException if the <tt>$xml</tt> is invalud.
	 * @see \Rhapsody\SetupBundle\Parser\ObjectParser::assert()
	 */
	public function assert($xml)
	{
		if ($xml === null) {
			throw new \NullPointerException('Unable to parse: null.');
		}

		if (!($xml instanceof XmlElement)) {
			throw new \InvalidArgumentException('Invalid input. '.get_class($this).' expects an XmlElement');
		}

		$name = $xml->getName();
		if (!in_array(strtolower($name), $this->elements)) {
			throw new \InvalidArgumentException('The element: '.$name.' was not a valid object-element and could not be parsed by '.get_class($this).'.');
		}

		$className = $xml->getAttribute('class');
		$ref = $xml->getAttribute('ref');
		if (empty($className) && empty($ref)) {
			throw new \InvalidArgumentException('An object must have either the "class" or "ref" attributes specified. Could not find either attributes in XML: '.strval($xml));
		}
	}
}