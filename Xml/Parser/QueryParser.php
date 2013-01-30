<?php
namespace Rhapsody\SetupBundle\Xml\Parser;

use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Parser\Parser;
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
class QueryParser extends Parser
{

	/**
	 *
	 */
	public static function parseQuery($xml)
	{
		$parser = new QueryParser();
		return $parser->parse($xml);
	}

	/**
	 * @param XmlElement $xml
	 * @see \Rhapsody\SetupBundle\Parser\Parser::assert()
	 */
	public function assert($xml)
	{
		if ($xml === null) {
			throw new \NullPointerException('Unable to parse: null.');
		}

		if (!($xml instanceof XmlElement)) {
			throw new \InvalidArgumentException('Invalid input. '.get_class($this).' expects an XmlElement');
		}

		$statement = $xml->getAttribute('statement');
		if (empty($statement)) {
			throw new \InvalidArgumentException('Invalid &lt;query>: No statement to execute.');
		}

		$type = $xml->getAttribute('type');
		if (empty($type)) {
			throw new \InvalidArgumentException('Unable to perform query on unknown type.');
		}
	}

	/**
	 * @return \Rhapsody\SetupBundle\Model\PropertyMetadata
	 * 		the metadata representing this object.
	 */
	public function parse($xml)
	{
		$this->assert($xml);
		$this->parseAttributes($xml);

		$query = new Query();
		$this->applyAttributes($query);
		return $query;
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

	public function applyAttributes(&$query)
	{
		$name = $this->getAttribute('name');
		if (!empty($name)) {
			$query->setName($name);
		}

		$projection = $this->getAttribute('projection');
		if (!empty($projection)) {
			$query->setProjection($projection);
		}

		$statement = $this->getAttribute('statement');
		if (!empty($query)) {
			$query->setStatement($statement);
		}

		$type = $this->getAttribute('type');
		if (!empty($type)) {
			$query->setType($type);
		}
	}
}