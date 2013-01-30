<?php
namespace Rhapsody\SetupBundle\Xml\Parser;

use Rhapsody\SetupBundle\Model\PropertyMetadata;
use Rhapsody\SetupBundle\Xml\Parser\PropertyParser;
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
class PropertyMetadataParser extends PropertyParser
{

	/**
	 *
	 */
	public static function parseMetadata($xml)
	{
		$parser = new PropertyMetadataParser();
		return $parser->parse($xml);
	}

	/**
	 * @return \Rhapsody\SetupBundle\Model\PropertyMetadata
	 * 		the metadata representing this object.
	 */
	public function parse($xml)
	{
		$this->assert($xml);
		$this->parseAttributes($xml);

		$metadata = new PropertyMetadata();
		$this->applyAttributes($metadata);
		return $metadata;
	}

	/**
	 *
	 * @param XmlElement $xml
	 */
	public function parseAttributes($xml)
	{
		$this->attributes = $xml->getAttributes();
		foreach ($this->attributes as $name => $value)
		{
			$key = strtolower(trim($name));
			$this->attributesMap[$key] = $name;
		}
	}

	public function applyAttributes(&$metadata)
	{
		$name = $this->getAttribute('name');
		if (!empty($name)) {
			$metadata->setName($name);
		}

		$query = $this->getAttribute('query');
		if (!empty($query)) {
			$metadata->setQuery($query);
		}

		$ref = $this->getAttribute('ref');
		if (!empty($ref)) {
			$metadata->setRef($ref);
		}

		$type = $this->getAttribute('type');
		if (!empty($type)) {
			$metadata->setType($type);
		}
	}
}