<?php
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