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
class Query
{
	/**
	 * The name of the property.
	 * @var string
	 * @access private
	 */
	private $_name;

	/**
	 * The projection
	 */
	private $_projection;

	/**
	 * The query operation to run; if a property references an element that was
	 * previously loaded into the data storage container, you may specify a
	 * lookup query to retrieve that object.
	 * @var string
	 * @access private
	 */
	private $_statement;

	/**
	 * The type of the property's value.
	 * @var string
	 * @access private
	 */
	private $_type;

	public function getTypeAsClassName()
	{
		$className = preg_replace("/[\.\\/\\\\]+/i", "\\", $this->_type);
		return $className;
	}

	public function getName()
	{
		return $this->_name;
	}

	public function getProjection()
	{
		return $this->_projection;
	}

	public function getStatement()
	{
		return $this->_statement;
	}

	public function getType()
	{
		return $this->_type;
	}

	public function hasName()
	{
		return !empty($this->_name);
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

	public function setProjection($projection)
	{
		$this->_projection = $projection;
	}

	public function setStatement($statement)
	{
		$this->_statement = $statement;
	}

	public function setType($type)
	{
		$this->_type = $type;
	}
}