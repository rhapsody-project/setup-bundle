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
namespace Rhapsody\SetupBundle\Test\Converter;

use Rhapsody\SetupBundle\Converter\ConverterFactory;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Converter
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class ConverterFactoryTest extends \PHPUnit_Framework_TestCase
{
	public function testGetIntegerType()
	{
		$factory = ConverterFactory::getInstance();
		$this->assertEquals('int', $factory->getType('1'));
	}

	public function testGetFloatType()
	{
		$factory = ConverterFactory::getInstance();
		$this->assertEquals('float', $factory->getType('1.1'));
	}

	public function testGetBooleanType()
	{
		$factory = ConverterFactory::getInstance();
		$this->assertEquals('bool', $factory->getType('true'));
		$this->assertEquals('bool', $factory->getType('yes'));
		$this->assertEquals('bool', $factory->getType('false'));
		$this->assertEquals('bool', $factory->getType('no'));
	}

	public function testGetStringType()
	{
		$factory = ConverterFactory::getInstance();
		$this->assertEquals('string', $factory->getType('hello'));
	}

	public function testGetNullType()
	{
		$factory = ConverterFactory::getInstance();
		$this->assertEquals('null', $factory->getType(null));
	}

	/*
	public function testGetArrayType()
	{
		// ** Not currently supported
		//$factory = ConverterFactory::getInstance();
		//$this->assertEquals('array', $factory->getType(array());
		//$this->assertEquals('array', $factory->getType(array('hello', 'world'));
		//$this->assertEquals('array', $factory->getType(array('greeting' => 'hello', 'subject' => 'world'));
	}

	public function testGetObjectType()
	{
		// ** Not currently supported
	}
	*/
}