<?php
namespace Rhapsody\SetupBundle\Queue;

use Rhapsody\SetupBundle\Model\Object;

/**
 *
 * @author Sean.Quinn
 *
 */
class DataObjectQueue implements \Iterator
{
	private $position = 0;
	private $queue = array();

	public function __construct()
	{
		$this->position = 0;
	}

	/**
	 *
	 * @param unknown $id
	 * @param Rhapsody\SetupBundle\Model\Object $object
	 */
	public function add($object)
	{
		array_push($this->queue, $object);
	}

	public function clear()
	{
		unset($this->queue);
		$this->queue = array();
	}

	public function count()
	{
		return count($this->queue);
	}

	/**
	 * (non-PHPDoc)
	 * @see Iterator::current()
	 */
	public function current()
	{
		return $this->queue[$this->position];
	}

	/**
	 * (non-PHPdoc)
	 * @see Iterator::key()
	 */
	public function key()
	{
		return $this->position;
	}

	/**
	 * (non-PHPDoc)
	 * @see Iterator::next()
	 */
	public function next()
	{
		++$this->position;
	}

	/**
	 * (non-PHPDoc)
	 * @see Iterator::rewind()
	 */
	public function rewind()
	{
		$this->position = 0;
	}

	/**
	 * (non-PHPDoc)
	 * @see Iterator::valid()
	 */
	public function valid()
	{
		return isset($this->queue[$this->position]);
	}
}
