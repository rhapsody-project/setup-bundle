<?php
namespace Rhapsody\SetupBundle\Test\Model;

use Rhapsody\SetupBundle\Model\ObjectMetadata;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Model
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class ObjectMetadataTest extends \PHPUnit_Framework_TestCase
{

	public function testSetClass()
	{
		$class = 'Rhapsody\SetupBundle\Test\Mocks\MockPerson';
		$expected = 'Rhapsody\SetupBundle\Test\Mocks\MockPerson';

		$metadata = new ObjectMetadata();
		$metadata->setClassName($class);
		$this->assertEquals($expected, $metadata->getClassName());
	}

	public function testSetClassFromPathName()
	{
		$class = 'Rhapsody/SetupBundle/Test/Mocks/MockPerson';
		$expected = 'Rhapsody\SetupBundle\Test\Mocks\MockPerson';

		$metadata = new ObjectMetadata();
		$metadata->setClassName($class);
		$this->assertEquals($expected, $metadata->getClassName());
	}

	public function testSetClassFromPackageName()
	{
		$class = 'Rhapsody.SetupBundle.Test.Mocks.MockPerson';
		$expected = 'Rhapsody\SetupBundle\Test\Mocks\MockPerson';

		$metadata = new ObjectMetadata();
		$metadata->setClassName($class);
		$this->assertEquals($expected, $metadata->getClassName());
	}
}