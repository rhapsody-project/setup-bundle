<?php
/* Copyright (c) 2013 Rhapsody Project
 *
 * Licensed under the MIT License (http://opensource.org/licenses/MIT)
 *
 * Permission is hereby granted, free of charge, to any
 * person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the
 * Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software,
 * and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice
 * shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY
 * KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE
 * WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR
 * PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS
 * OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR
 * OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT
 * OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
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
