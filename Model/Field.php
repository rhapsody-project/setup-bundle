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