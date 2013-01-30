<?php
/* Copyright 2009 PHP Commons
 *
* Licensed under the Apache License, Version 2.0
* (the "License"); you may not use this file except
* in compliance with the License. You may obtain a
* copy of the License at
*
*   http://www.apache.org/licenses/LICENSE-2.0
*
* Unless required by applicable law or agreed to in
* writing, software distributed under the License is
* distributed on an "AS IS" BASIS, WITHOUT WARRANTIES
* OR CONDITIONS OF ANY KIND, either express or implied.
* See the License for the specific language governing
* permissions and limitations under the License.
*/
namespace Rhapsody\SetupBundle\Test\Xml;

use Rhapsody\SetupBundle\Xml\XmlElement;

/**
 *
 * @author 	  Sean W. Quinn
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Xml
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 */
class XmlElementTest extends \PHPUnit_Framework_TestCase
{

	public function testAppendChild()
	{
	}

	public function testGet()
	{
		$xml = '<element><one></one></element>';
		$element = new XmlElement($xml);

		$result = $element->get('one');
		$this->assertNotNull($result);
		$this->assertEquals('one', $result->getName());
	}

	public function testGetSingularFlattenFalse()
	{
		$xml = '<element><one></one></element>';
		$element = new XmlElement($xml);

		$result = $element->get('one', false);
		$this->assertNotNull($result);
		$this->assertEquals(1, sizeof($result));
		$this->assertEquals('one', $result[0]->getName());
	}

	public function testGetMultiple()
	{
		$xml = '<element><one></one><one></one><two></two></element>';
		$element = new XmlElement($xml);

		$result = $element->get('one');
		$this->assertNotNull($result);
		$this->assertEquals(2, sizeof($result));
		$this->assertEquals('one', $result[0]->getName());
		$this->assertEquals('one', $result[1]->getName());
	}

	/**
	 * @param $name
	 * @return unknown_type
	 */
	public function testGetAll()
	{
		$xml = '<element><one></one><one></one><two></two></element>';
		$element = new XmlElement($xml);

		$result = $element->getAll('one');
		$this->assertNotNull($result);
		$this->assertEquals(2, sizeof($result));
	}

	/**
	 * @param $name
	 * @return unknown_type
	 */
	public function testGetAllArray()
	{
		$xml = '<element><one></one><one></one><two></two></element>';
		$element = new XmlElement($xml);

		$result = $element->getAll(array('one', 'two'));
		$this->assertNotNull($result);
		$this->assertEquals(3, sizeof($result));
	}

	/**
	 * @param $attributeName
	 * @return unknown_type
	 */
	public function testGetAttribute()
	{
		$xml = '<element greeting="hello" />';
		$element = new XmlElement($xml);

		$result = $element->getAttribute('greeting');
		$this->assertNotNull($result);
		$this->assertEquals('hello', $result);
	}

	public function testGetAttributeNonExistant()
	{
		$xml = '<element greeting="hello" />';
		$element = new XmlElement($xml);

		$result = $element->getAttribute('subject');
		$this->assertNull($result);
	}

	public function testGetAttributeNonExistantWithDefault()
	{
		$xml = '<element greeting="hello" />';
		$element = new XmlElement($xml);

		$result = $element->getAttribute('subject', 'world');
		$this->assertNotNull($result);
		$this->assertEquals('world', $result);
	}

	public function testGetAttributes()
	{
		$xml = '<element greeting="hello" subject="world" />';
		$element = new XmlElement($xml);

		$result = $element->getAttributes();
		$this->assertNotNull($result);
		$this->assertNotEmpty($result);
		$this->assertEquals(array('greeting' => 'hello', 'subject' => 'world'), $result);
	}

	public function testGetFirstChild()
	{
		$xml = '<element><child>Hello</child><child>World</child></element>';
		$element = new XmlElement($xml);

		$result = $element->getFirstChild();
		$this->assertNotNull($result);
		$this->assertEquals('child', $result->getName());
		$this->assertEquals('Hello', $result->getValue());
	}

	public function testGetFirstAndOnlyChild()
	{
		$xml = '<element><child>Hello</child></element>';
		$element = new XmlElement($xml);

		$result = $element->getFirstChild();
		$this->assertNotNull($result);
		$this->assertEquals('child', $result->getName());
		$this->assertEquals('Hello', $result->getValue());
	}

	public function testGetFirstChildEmptyChild()
	{
		$xml = '<element><child></child><child>World</child></element>';
		$element = new XmlElement($xml);

		$result = $element->getFirstChild();
		$this->assertNotNull($result);
		$this->assertEquals('child', $result->getName());
		$this->assertEquals(null, $result->getValue());
	}

	public function testGetFirstChildNoChildren()
	{
		$xml = '<element></element>';
		$element = new XmlElement($xml);

		$result = $element->getFirstChild();
		$this->assertNull($result);
	}


	/**
	 *
	 */
	public function testGetRequiredAttribute()
	{
		$xml = '<element />';
		$element = new XmlElement($xml);

		// TODO: use expectedException
		try {
			$result = $element->getRequiredAttribute('greeting');
		}
		catch (\Exception $ex) {
			// expected.
		}
	}

	public function testGetUnique()
	{
	}

	public function testToArray()
	{
	}

	public function testGetValue()
	{
		$xml = '<element><value></value></element>';
		$element = new XmlElement($xml);

		$result = $element->getValue();
		$this->assertNotNull($result);
	}

	public function testGetValueTextNode()
	{
		$xml = '<element>value</element>';
		$element = new XmlElement($xml);

		$result = $element->getValue();
		$this->assertNotNull($result);
		$this->assertEquals('value', $result);
	}
}