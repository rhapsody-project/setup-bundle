<?php
namespace Rhapsody\SetupBundle\Test\Type;

use Rhapsody\SetupBundle\Type\String;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Type
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class StringTest extends \PHPUnit_Framework_TestCase
{

	public function testConvert()
	{
		$converter = new String();

		$this->assertEquals('hello', $converter->convert('hello'));
		$this->assertEquals('hello', $converter->convert('hello '));
		$this->assertEquals('hello world', $converter->convert(' hello world '));

	}

	public function testConvertCDATA()
	{
		$converter = new String();

		$xml = simplexml_load_string('<test><![CDATA[hello world]]></test>');
		$this->assertEquals('hello world', $converter->convert(strval($xml)));
	}
}