<?php
namespace Rhapsody\SetupBundle\Test\Model;

use Rhapsody\SetupBundle\Model\Object;
use Rhapsody\SetupBundle\Model\ObjectMetadata;
use Rhapsody\SetupBundle\Model\Property;
use Rhapsody\SetupBundle\Model\PropertyMetadata;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Model
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class ObjectTest extends \PHPUnit_Framework_TestCase
{

	public function createProperty($name, $value)
	{
		$metadata = new PropertyMetadata();
		$metadata->setName($name);
		$property = new Property($metadata, $value);
		return $property;
	}

	public function testNew()
	{
		$metadata = new ObjectMetadata();
		$metadata->setClassName('Rhapsody\SetupBundle\Test\Mocks\MockPerson');

		$obj = new Object($metadata);
		$this->assertNotNull($obj);
		$this->assertNotNull($obj->getClass());
		$this->assertNotNull($obj->getClassName());
		$this->assertNotNull($obj->getInstance());

		$expected = 'Rhapsody\SetupBundle\Test\Mocks\MockPerson';
		$this->assertEquals($expected, $obj->getClassName());
	}

	public function testSetProperty()
	{
		$name = 'John Smith';
		$property = $this->createProperty('name', $name);

		$metadata = new ObjectMetadata();
		$metadata->setClassName('Rhapsody.SetupBundle.Test.Mocks.MockPerson');

		$obj = new Object($metadata);
		$obj->set($property);
		$this->assertEquals($name, $obj->getInstance()->getName());
	}
}