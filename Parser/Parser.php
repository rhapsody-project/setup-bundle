<?php
namespace Rhapsody\SetupBundle\Parser;

use Monolog\Logger;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Parser
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
abstract class Parser
{
	/**
	 * The array of parsed attributes.
	 * @var array
	 * @access protected
	 */
	protected $attributes = array();

	/**
	 * An associative array of attribute case-insensitive key values to the
	 * name indices of the attributes stored in the <tt>$attributes</tt> array.
	 * @var array
	 * @access protected
	 */
	protected $attributesMap = array();

	protected $log = null;

	public function assert($input)
	{
		// No-op
	}

	/**
	 *
	 */
	public function getAttribute($name)
	{
		$key = strtolower(trim($name));
		if (array_key_exists($key, $this->attributesMap)) {
			$attrName = $this->attributesMap[$key];
			return $this->attributes[$attrName];
		}
		return null;
	}

	public function getAttributes()
	{
		return $this->attributes;
	}

	/**
	 *
	 */
	public function getBooleanAttribute($name)
	{
		$booleans = array('true' => true, 'yes' => true, 'false' => false, 'no' => false);
		$value = $this->getAttribute($name);
		$bool = strtolower(trim($value));
		if (!is_bool($value) && array_key_exists($bool, $booleans)) {
			return $booleans[$bool];
		}
		return is_bool($value) ? $value : false;
	}

	/**
	 *
	 * @return \Monolog\Logger
	 */
	protected function getLog()
	{
		if ($this->log === null) {
			$this->log = new Logger(get_called_class());
		}
		return $this->log;
	}

	public function hasAttribute($name)
	{
		$key = strtolower(trim($name));
		if (array_key_exists($key, $this->attributesMap)) {
			return true;
		}
		return false;
	}

	/**
	 *
	 */
	public function parse($input)
	{
		$this->assert($input);
	}

	public function setAttributes(array $attributes = array())
	{
		$this->attributes = $attributes;
		unset($this->attributesMap);
		foreach ($attributes as $name => $value)
		{
			$key = strtolower(trim($name));
			$this->attributesMap[$key] = $name;
		}
	}
}