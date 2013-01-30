<?php
namespace Rhapsody\SetupBundle\Test\Type;

use Rhapsody\SetupBundle\Type\NullValue;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Type
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class NullValueTest extends \PHPUnit_Framework_TestCase
{

	public function testConvert()
	{
		$converter = new NullValue();

		$this->assertNull($converter->convert(''));
		$this->assertNull($converter->convert('abc'));
		$this->assertNull($converter->convert(1));
		$this->assertNull($converter->convert(null));
	}
}