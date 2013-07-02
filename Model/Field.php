<?php
namespace Rhapsody\SetupBundle\Model;

/**
 *
 * @author Sean.Quinn
 * @since 1.0
 */
class Field implements FieldInterface
{

	/**
	 * The name of the field.
	 * @var string
	 * @access private
	 */
	private $name;

	/**
	 * If an index was specified on a field, e.g. <tt>foo['bar']</tt>, this
	 * property represents the index, <tt>bar</tt>.
	 * @var mixed
	 * @access private
	 */
	private $index;

	public function __construct($name, $index = null)
	{
		$this->name = $name;
		if (!empty($index)) {
			$this->index = $index;
		}
	}

	/**
	 *
	 * @param mixed $object
	 * @throws \OutOfBoundsException
	 * @return mixed
	 */
	public function resolve($object)
	{
		$className = get_class($object);
		$property = new \ReflectionProperty($className, $this->name);
		$property->setAccessible(true);
		$value = $property->getValue($object);
		if (is_array($value) && !empty($this->index)) {
			if (!array_key_exists($this->index, $value)) {
				throw new \OutOfBoundsException('The index: '.$this->index
						.' does not exist for the object property: '.$this->name
						.' on the object: '.$className);
			}
			return $value[$this->index];
		}
		return $value;
	}
}