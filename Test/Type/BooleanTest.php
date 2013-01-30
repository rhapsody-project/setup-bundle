<?php
namespace Rhapsody\SetupBundle\Test\Type;

use Rhapsody\SetupBundle\Type\Boolean;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Type
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class BooleanTest extends \PHPUnit_Framework_TestCase
{

	public function testConvert()
	{
		$converter = new Boolean();

		$this->assertTrue($converter->convert(true));
		$this->assertFalse($converter->convert(false));

		$this->assertTrue($converter->convert('true'));
		$this->assertTrue($converter->convert('True'));
		$this->assertTrue($converter->convert('TRUE'));

		$this->assertTrue($converter->convert('yes'));
		$this->assertTrue($converter->convert('Yes'));
		$this->assertTrue($converter->convert('YES'));

		$this->assertFalse($converter->convert('false'));
		$this->assertFalse($converter->convert('False'));
		$this->assertFalse($converter->convert('FALSE'));

		$this->assertFalse($converter->convert('no'));
		$this->assertFalse($converter->convert('No'));
		$this->assertFalse($converter->convert('NO'));
	}

	public function testInvalidConvert()
	{
		$converter = new Boolean();

		$this->assertFalse($converter->convert('!true'));
		$this->assertFalse($converter->convert('invalid'));
		$this->assertFalse($converter->convert('nein'));
		$this->assertFalse($converter->convert('unknown'));
	}
}