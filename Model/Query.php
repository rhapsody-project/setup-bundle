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