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
class PropertyMetadata
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
	private $_query;

	/**
	 * The name of the object that this property references.
	 * @var string
	 * @access private
	 */
	private $_ref;

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

	public function getQuery()
	{
		return $this->_query;
	}

	public function getRef()
	{
		return $this->_ref;
	}

	public function getType()
	{
		return $this->_type;
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

	public function setProjection($projection)
	{
		$this->_projection = $projection;
	}

	public function setQuery($query)
	{
		$this->_query = $query;
	}

	public function setRef($ref)
	{
		$this->_ref = $ref;
	}

	public function setType($type)
	{
		$this->_type = $type;
	}
}