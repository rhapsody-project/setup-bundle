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
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Model
 * @copyright Copyright (c) 2013 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class ObjectMetadata
{

	/**
	 * Whether the {@link Rhapsody\SetupBundle\Model\Object} is marked as
	 * <tt>abstract</tt> or not. An object marked as abstract should not be
	 * persisted, but it should be added to the cache.
	 * @var boolean
	 * @access private
	 */
	private $_abstract = false;

	/**
	 * The [fully qualified] class name.
	 * @var string
	 * @access private
	 */
	private $_className;

	/**
	 * The name which identifies this object from others.
	 * @var string
	 * @access private
	 */
	private $_name;

	/**
	 * A reference, by ID, to the parent of the {@link Rhapsody\SetupBundle\Model\Object}.
	 * If a parent is identified, the object will inherit all properties that
	 * have been set from the parent. An object may not have both a
	 * <tt>parent</tt> and <tt>ref</tt> attribute identified. This should throw
	 * an exception.
	 * @var string
	 * @access private
	 */
	private $_parentRef = null;

	/**
	 * A reference to another object to use. A reference object effectively
	 * turns the object node into a symbolic link to another defined object.
	 * @var string
	 * @access private
	 */
	private $_ref = null;

	/**
	 * Whether an object is <i>transient</i> or not. Like <tt>abstract</tt>
	 * objects, <tt>transient</tt> objects are never queued for persistence.
	 * @var boolean
	 * @access private
	 */
	private $_transient = false;

	/**
	 *
	 */
	public function getClassName()
	{
		return $this->_className;
	}

	public function getName()
	{
		return $this->_name;
	}

	/**
	 *
	 */
	public function getParentRef()
	{
		return $this->_parentRef;
	}

	/**
	 *
	 */
	public function getRef()
	{
		return $this->_ref;
	}

	/**
	 *
	 */
	public function isAbstract()
	{
		return $this->_abstract;
	}

	/**
	 *
	 */
	public function isTransient()
	{
		return $this->_transient;
	}

	/**
	 *
	 */
	public function setAbstract($abstract)
	{
		$this->_abstract = $abstract;
	}

	/**
	 *
	 */
	public function setClassName($className)
	{
		$className = preg_replace("/[\.\\/\\\\]+/i", "\\", $className);
		$this->_className = $className;
	}

	public function setName($name)
	{
		$this->_name = $name;
	}

	/**
	 *
	 */
	public function setParentRef($parentRef)
	{
		$this->_parentRef = $parentRef;
	}

	/**
	 *
	 */
	public function setRef($ref)
	{
		$this->_ref = $ref;
	}

	/**
	 *
	 */
	public function setTransient($transient)
	{
		$this->_transient = is_bool($transient) ? $transient : false;
	}
}