<?php
namespace Rhapsody\SetupBundle\Test\Type;

use Rhapsody\SetupBundle\Type\Numeric;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Type
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class NumericTest extends \PHPUnit_Framework_TestCase
{

	public function testConvert()
	{
		$converter = new Numeric();

		$this->assertEquals(1, $converter->convert('1'));
		$this->assertEquals(1.5, $converter->convert('1.5'));
	}
}