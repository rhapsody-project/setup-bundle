<?php
namespace Rhapsody\SetupBundle\Model;

use Rhapsody\SetupBundle\Model\PropertyMetadata;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Populator
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class Object
{
	/**
	 * The reflection class
	 * @var \ReflectionClass
	 * @access private
	 */
	private $__class;

	/**
	 * The identifier of the object; this is the ID assigned by the data storage
	 * layer when an object is persisted.
	 * @var string the identifier of this object's instance.
	 */
	private $id;

	/**
	 * The instance of this object.
	 * @var mixed
	 * @access private
	 */
	private $instance = null;

	/**
	 * The metadata that describes this object.
	 * @var ObjectMetadata
	 * @access private
	 */
	private $metadata;

	/**
	 * The properties
	 */
	private $properties = array();

	public static function fromInstance($name = null, $instance)
	{
		$className = get_class($instance);
		$class = new \ReflectionClass($className);

		$metadata = new ObjectMetadata();
		$metadata->setClassName($className);
		$metadata->setName($name);

		$properties = array();
		$allProperties = \ReflectionProperty::IS_PUBLIC | \ReflectionProperty::IS_PROTECTED | \ReflectionProperty::IS_PRIVATE;
		$reflectionProperties = $class->getProperties($allProperties);
		foreach ($reflectionProperties as $prop) {
			$prop->setAccessible(true);
			$name = $prop->getName();
			$value = $prop->getValue($instance);
			$type = gettype($value) === 'object' ? get_class($value) : gettype($value);

			$propertyMeta = new PropertyMetadata();
			$propertyMeta->setName($name);
			$propertyMeta->setType($type);

			$property = new Property($propertyMeta);
			$property->setValue($value);
			array_push($properties, $property);
		}

		$object = new Object($metadata);
		$object->setInstance($instance);
		$object->setProperties($properties);
		return $object;
	}

	public function __construct(ObjectMetadata $metadata)
	{
		if ($metadata === null) {
			throw new \NullPointerException('Data object requires metadata.');
		}

		$this->metadata = $metadata;

		$className = $metadata->getClassName();
		if (empty($className)) {
			throw new \NullPointerException('Unable to create new Object. An invalid class name was given.');
		}
		$this->__class = new \ReflectionClass($className);
		$this->instance = $this->__class->newInstance();
	}

	public function __toString()
	{
		if ($this->instance !== null) {
			return 'Object[Object='.$this->getClassName().'@'.spl_object_hash($this->instance).']';
		}
		return 'Object[Object='.$this->getClassName();
	}

	public function addProperty(Property $property)
	{
		$name = $property->getName();
		$this->properties[$name] = $property;
	}

	/**
	 *
	 */
	public function get(Property $property)
	{
		try {
			$name = $property->getName();

			$reflectionProperty = $this->__class->getProperty($property->getName());
			$reflectionProperty->setAccessible(true);
			return $reflectionProperty->getValue($this->instance);
		}
		catch (\ReflectionException $ex) {
			throw new \ReflectionException($ex->getMessage().' on class '.$this->__className, $ex->getCode(), $ex);
		}
	}

	/**
	 *
	 * @param string $property the name of the property being set.
	 * @param mixed $value the value to set the <tt>$property</tt> to.
	 * @return void
	 */
	public function set(Property $property, $value = null)
	{
		try {
			$name = $property->getName();
			if ($value === null) {
				$value = $property->getValue();
			}

			$reflectionProperty = $this->__class->getProperty($property->getName());
			$reflectionProperty->setAccessible(true);
			$reflectionProperty->setValue($this->instance, $value);
		}
		catch (\ReflectionException $ex) {
			throw new \ReflectionException($ex->getMessage().' on class '.$this->metadata->getClassName(), $ex->getCode(), $ex);
		}
	}

	/**
	 * Returns the {@link \ReflectionClass}.
	 * @return \ReflectionClass the reflection class.
	 */
	public function getClass()
	{
		return $this->__class;
	}

	public function getClassName()
	{
		return $this->metadata->getClassName();
	}

	public function getId()
	{
		return $this->id;
	}

	public function getInstance()
	{
		return $this->instance;
	}

	/**
	 * <p>
	 * Returns the name of this object.
	 * </p>
	 *
	 * @return string the name of this object.
	 * @see ObjectMetadata::getName()
	 */
	public function getName()
	{
		return $this->metadata->getName();
	}

	public function getParentRef()
	{
		return $this->metadata->getParentRef();
	}

	public function getProperties()
	{
		return $this->properties;
	}

	public function hasParent()
	{
		return ($this->metadata->getParentRef() !== null);
	}

	public function inherit(Object $object)
	{
		$instance = $object->getInstance();
		$properties = $object->getProperties();
		foreach ($properties as $name => $property) {
			$this->set($property);
		}
	}

	public function isAbstract()
	{
		return $this->metadata->isAbstract();
	}

	public function isPersistable()
	{
		$abstract = $this->metadata->isAbstract();
		$transient = $this->metadata->isTransient();
		if ($abstract === true || $transient === true) {
			return false;
		}
		return true;
	}

	public function isTransient()
	{
		return $this->metadata->isTransient();
	}

	/**
	 *
	 * @param string $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 *
	 * @param mixed $instance
	 */
	public function setInstance($instance)
	{
		$this->instance = $instance;
	}

	public function setProperties($properties = array())
	{
		$this->properties = $properties;
	}
}