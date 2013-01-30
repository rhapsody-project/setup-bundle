<?php
namespace Rhapsody\SetupBundle\Model;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Model
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class Property
{
	/**
	 * The property metadata
	 * @var PropertyMetadata
	 * @access private
	 */
	private $metadata;

	/**
	 * The the property's value.
	 * @var mixed
	 * @access private
	 */
	private $value;

	public function __construct($metadata, $value = null)
	{
		$this->metadata = $metadata;
		$this->value = $value;
	}

	public function getMetadata()
	{
		return $this->metadata;
	}

	public function getName()
	{
		return $this->metadata->getName();
	}

	public function getValue()
	{
		return $this->value;
	}

	public function setValue($value)
	{
		$this->value = $value;
	}
}