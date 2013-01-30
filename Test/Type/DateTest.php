<?php
namespace Rhapsody\SetupBundle\Test\Type;

use Rhapsody\SetupBundle\Type\Date;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Type
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class DateTest extends \PHPUnit_Framework_TestCase
{

	public function testConvert()
	{
		$converter = new Date();
		$time = time();
		$date = date('c', $time);

		$this->assertEquals($time, $converter->convert($date, array('format' => 'c')));
		$this->assertEquals($time, $converter->convert($time, array('format' => 'U')));
	}

	public function testExplicitDateConvert()
	{
		$converter = new Date();
		$date = '2013-01-01T00:00:00+00:00';
		$time = strtotime($date);

		$this->assertEquals($time, $converter->convert($date, array('format' => 'c')));
	}
}