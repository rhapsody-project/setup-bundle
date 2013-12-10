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