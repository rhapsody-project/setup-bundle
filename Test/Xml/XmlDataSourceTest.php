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

use Rhapsody\SetupBundle\Populator\NoopPopulator;
use Rhapsody\SetupBundle\Test\Mocks\MockPerson;
use Rhapsody\SetupBundle\Test\Mocks\MockAddress;
use Rhapsody\SetupBundle\Xml\XmlDataSource;
use Rhapsody\SetupBundle\Xml\XmlElement;
use Symfony\Bridge\Monolog\Logger;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Data
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class XmlDataSourceTest extends \PHPUnit_Framework_TestCase
{

	const EMPTY_FILE = 'test/empty';
	const TEST_COLLISION_DATA_FILE = 'test/xml/test-collision.xml';
	const TEST_DATA_FILE = 'test/xml/test.xml';
	const TEST_QUERY_DATA_FILE = 'test/xml/test-query.xml';
	const TEST_REF_DATA_FILE = 'test/xml/test-ref.xml';


	/**
	 *
	 * @param unknown $fileName
	 * @return \Rhapsody\SetupBundle\Xml\XmlDataSource
	 */
	private function createDataSource($fileName = XmlDataSourceTest::EMPTY_FILE)
	{
		$file = $this->getResource($fileName);
		$populator = $this->getPopulator();
		$dataSource = new XmlDataSource($file, $populator);
		return $dataSource;
	}

	/**
	 *
	 * @param XmlDataSource $dataSource
	 * @param unknown $content
	 * @return XmlDataSource
	 */
	private function setXml(XmlDataSource $dataSource, $content)
	{
		// ** Convert the XML Content to an actual XML object...
		$xml = simplexml_load_string($content, 'Rhapsody\SetupBundle\Xml\XmlElement');

		// ** Set the XML.
		$property = new \ReflectionProperty(get_class($dataSource), 'xml');
		$property->setAccessible(true);
		$property->setValue($dataSource, $xml);
		$property->setAccessible(false);
		return $dataSource;
	}

	private function getResourcesDirectory()
	{
		$resources  = dirname(__FILE__);
		$resources .= DIRECTORY_SEPARATOR.'..';
		$resources .= DIRECTORY_SEPARATOR.'..';
		$resources .= DIRECTORY_SEPARATOR.'Resources';
		return $resources;
	}

	/**
	 * Returns the path to  the resource file.
	 * @param string $file the file.
	 * @return string the real path of the resource.
	 */
	private function getResource($file)
	{
		$resources = $this->getResourcesDirectory();
		return realpath($resources.DIRECTORY_SEPARATOR.$file);
	}

	/**
	 *
	 * @return \Rhapsody\SetupBundle\NoopPopulator
	 */
	private function getPopulator()
	{
		$values = array('logger' => new Logger('test'));
		$container = $this->getMock('Symfony\Component\DependencyInjection\ContainerInterface');
		$container->expects($this->any())->method('get')->will($this->returnCallback(
			function($id) use ($values) {
				return $values[$id];
			}
		));

		$populator = new NoopPopulator();
		$populator->setContainer($container);
		return $populator;
	}

	public function testNewXmlDataSource()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		$this->assertNotNull($dataSource);
		$this->assertNotEmpty($dataSource->getFile());
		$this->assertEquals($file, $dataSource->getFile());
	}

	public function testPrepare()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$populator = $this->getPopulator();
		$dataSource = new XmlDataSource($file, $populator);

		$dataSource->prepare();

		$this->assertNotNull($dataSource->getXml());
		$this->assertTrue($dataSource->isPrepared());
		$this->assertEquals(2, count($dataSource->getIndex()));
		$this->assertTrue($dataSource->contains('mockperson-1'));
		$this->assertTrue($dataSource->contains('mockperson-2'));
	}

	public function testGetIntegerValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for integer...
		$xml = '<property name="test" value="1" />';
		$element = new XmlElement($xml);
		$value = $dataSource->getValue($element);
		$this->assertEquals(1, $value);
	}


	public function testGetFloatValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for float...
		$xml = '<property name="test" value="1.1" />';
		$element = new XmlElement($xml);
		$value = $dataSource->getValue($element);
		$this->assertEquals(1.1, $value);
	}

	public function testGetBooleanValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for boolean...
		$xml = '<property name="test" value="true" />';
		$element = new XmlElement($xml);
		$value = $dataSource->getValue($element);
		$this->assertTrue($value);
	}

	public function testGetStringValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for string...
		$xml = '<property name="test" value="hello" />';
		$element = new XmlElement($xml);
		$value = $dataSource->getValue($element);
		$this->assertEquals('hello', $value);
	}

	public function testGetNullValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for null...
		$xml = '<property name="test" />';
		$element = new XmlElement($xml);
		$value = $dataSource->getValue($element);
		$this->assertEquals(null, $value);
	}

	public function testGetArrayValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for array...
		$xml = '<property name="test"><array><entry value="1" /><entry value="2" /></array></property>';
		$element = new XmlElement($xml);

		// ** Confirm that the first child is not null, and does in fact exist...
		$firstChild = $element->getFirstChild();
		$this->assertNotNull($firstChild);
		$this->assertEquals('array', $firstChild->getName());

		// ** Process value from populator's getValue() method..
		$value = $dataSource->getValue($element);
		$this->assertEquals(array(), $value);
	}

	public function testGetEmptyArrayValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for array...
		$xml = '<property name="test"><array></array></property>';
		$element = new XmlElement($xml);

		// ** Confirm that the first child is not null, and does in fact exist...
		$firstChild = $element->getFirstChild();
		$this->assertNotNull($firstChild);
		$this->assertEquals('array', $firstChild->getName());

		// ** Process value from populator's getValue() method..
		$value = $dataSource->getValue($element);
		$this->assertEquals(array(), $value);
	}

	public function testGetObjectValue()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for object...
		$xml = '<property name="test"><object class="Rhapsody/SetupBundle/Test/Mocks/MockPerson"><property name="name" value="John Smith" /></object></property>';
		$element = new XmlElement($xml);
		$value = $dataSource->getValue($element);
		$this->assertNotNull($value);
		$this->assertEquals('Rhapsody\SetupBundle\Test\Mocks\MockPerson', get_class($value));
	}

	public function testGetReference()
	{
		$xml = <<<EOT
<object class="Rhapsody/SetupBundle/Test/Mocks/MockPerson">
	<property name="name" value="John Smith" />
	<property name="address">
		<object class="Rhapsody/SetupBundle/Test/Mocks/MockAddress">
			<property name="address1" value="1 Main St." />
			<property name="city" value="Boston" />
			<property name="state" value="MA" />
		</object>
	</property>
</object>
EOT;

		// TODO:
	}

	public function testFindObject()
	{
		$xml = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<rhapsody:data xmlns:rhapsody="http://github.com/rhapsody-project/RhapsodySetupBundle">
	<object name="john-smith" class="Rhapsody/SetupBundle/Test/Mocks/MockPerson">
		<property name="name" value="John Smith" />
	</object>
	<object class="Rhapsody/SetupBundle/Test/Mocks/MockEmployee">
		<property name="person" ref="john-smith" />
	</object>
</rhapsody:data>
EOT;

		$dataSource = $this->createDataSource();
		$this->setXml($dataSource, $xml);

		$actual = $dataSource->findObject('john-smith');
		$this->assertNotNull($actual);

		$object = $actual->getInstance();
		$this->assertEquals('John Smith', $object->getName());
	}

	public function testParseArray()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for object...
		$xml = <<<EOT
<array>
	<value>1</value>
	<value>2</value>
	<value>3</value>
	<value>4</value>
	<value>5</value>
</array>
EOT;
		$element = new XmlElement($xml);
		$array = $dataSource->parseArray($element);
		$this->assertNotNull($array);
		$this->assertNotEmpty($array);
		$this->assertEquals(5, count($array));
	}

	public function testParseObject()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$dataSource = new XmlDataSource($file, $this->getPopulator());

		// ** Assert type derivation for object...
		$xml = <<<EOT
<object class="Rhapsody/SetupBundle/Test/Mocks/MockPerson">
	<property name="name" value="John Smith" />
	<property name="address">
		<object class="Rhapsody/SetupBundle/Test/Mocks/MockAddress">
			<property name="address1" value="1 Main St." />
			<property name="city" value="Boston" />
			<property name="state" value="MA" />
		</object>
	</property>
</object>
EOT;
		$element = new XmlElement($xml);
		$dataObject = $dataSource->parseObject($element);
		$this->assertNotNull($dataObject);

		$object = $dataObject->getInstance();
		$this->assertNotNull($object);
		$this->assertEquals('Rhapsody\SetupBundle\Test\Mocks\MockPerson', get_class($object));
		$this->assertEquals('John Smith', $object->getName());
		$this->assertNotNull($object->getAddress());
		$this->assertEquals('1 Main St.', $object->getAddress()->getAddress1());
		$this->assertEquals('Boston', $object->getAddress()->getCity());
		$this->assertEquals('MA', $object->getAddress()->getState());
	}

	public function testProcess()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_DATA_FILE);
		$populator = $this->getPopulator();
		$dataSource = new XmlDataSource($file, $populator);

		$dataSource->process();

		$this->assertEquals(2, $populator->getCache()->count());
		$this->assertTrue($populator->getCache()->has('mockperson-1'));

		$actual = $populator->getCache()->get('mockperson-1');
		$this->assertEquals('John Smith', $actual->getInstance()->getName());
		$this->assertEquals('77 Massachusetts Ave.', $actual->getInstance()->getAddress()->getAddress1());

		$this->assertTrue($populator->getCache()->has('mockperson-2'));
		$actual = $populator->getCache()->get('mockperson-2');
		$this->assertEquals('Jane Smith', $actual->getInstance()->getName());
		$this->assertEquals('Massachusetts Hall', $actual->getInstance()->getAddress()->getAddress1());
	}

	public function testProcessWithQuery()
	{
		$mock = new MockPerson();
		$mock->setName('James Smith');

		$file = $this->getResource(XmlDataSourceTest::TEST_QUERY_DATA_FILE);
		$populator = $this->getPopulator();
		$populator->mockQueryResult($mock);
		$dataSource = new XmlDataSource($file, $populator);

		$dataSource->process();

		$this->assertEquals(3, $populator->getCache()->count());

		$this->assertTrue($populator->getCache()->has('mockperson-1'));
		$actual = $populator->getCache()->get('mockperson-1');
		$this->assertEquals('John Smith', $actual->getInstance()->getName());
		$this->assertEquals('77 Massachusetts Ave.', $actual->getInstance()->getAddress()->getAddress1());

		$this->assertTrue($populator->getCache()->has('mockperson-2'));
		$actual = $populator->getCache()->get('mockperson-2');
		$this->assertEquals('Jane Smith', $actual->getInstance()->getName());
		$this->assertEquals('Massachusetts Hall', $actual->getInstance()->getAddress()->getAddress1());

		$this->assertTrue($populator->getCache()->has('mockperson-3'));
		$actual = $populator->getCache()->get('mockperson-3');
		$this->assertEquals('James Smith', $actual->getInstance()->getName());
		$this->assertEquals(null, $actual->getInstance()->getAddress());
	}

	public function testProcessWithReferences()
	{
		$file = $this->getResource(XmlDataSourceTest::TEST_REF_DATA_FILE);
		$populator = $this->getPopulator();
		$dataSource = new XmlDataSource($file, $populator);

		$dataSource->process();

		$this->assertEquals(4, $populator->getCache()->count());

		$this->assertTrue($populator->getCache()->has('mockperson-1'));
		$this->assertTrue($populator->getCache()->has('mit-address'));
		$actual = $populator->getCache()->get('mockperson-1');
		$this->assertEquals('John Smith', $actual->getInstance()->getName());
		$this->assertEquals('77 Massachusetts Ave.', $actual->getInstance()->getAddress()->getAddress1());

		$this->assertTrue($populator->getCache()->has('mockperson-2'));
		$this->assertTrue($populator->getCache()->has('harvard-address'));
		$actual = $populator->getCache()->get('mockperson-2');
		$this->assertEquals('Jane Smith', $actual->getInstance()->getName());
		$this->assertEquals('Massachusetts Hall', $actual->getInstance()->getAddress()->getAddress1());
	}
}