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
namespace Rhapsody\SetupBundle\Test\Xml\Parser;

use Monolog\Logger;

use Rhapsody\SetupBundle\Model\ObjectMetadata;
use Rhapsody\SetupBundle\Xml\XmlElement;
use Rhapsody\SetupBundle\Xml\Parser\ObjectMetadataParser;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Xml\Parser
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class ObjectMetadataParserTest extends \PHPUnit_Framework_TestCase
{

	public function getLog()
	{
		return new Logger(get_class($this));
	}

	public function testAssert()
	{
		$xml = '<object class="My\Test\Class" />';
		$element = new XmlElement($xml);

		$parser = new ObjectMetadataParser();
		$parser->assert($element);
	}

	public function testAssertParentAndRef()
	{
		$this->setExpectedException('InvalidArgumentException');

		$xml = '<object parent="MyParent" ref="ThatOtherClass" />';
		$element = new XmlElement($xml);
		$parser = new ObjectMetadataParser();
		$parser->assert($element);
	}

	public function testAssertNoClassNoRef()
	{
		$this->setExpectedException('InvalidArgumentException');

		$xml = '<object />';
		$element = new XmlElement($xml);
		$parser = new ObjectMetadataParser();
		$parser->assert($element);
	}

	public function testAssertNonObjectElement()
	{
		$this->setExpectedException('InvalidArgumentException');

		$xml = '<notanobject />';
		$element = new XmlElement($xml);
		$parser = new ObjectMetadataParser();
		$parser->assert($element);
	}

	public function testAssertNull()
	{
		$this->setExpectedException('NullPointerException');

		$parser = new ObjectMetadataParser();
		$parser->assert(null);
	}

	public function testApplyAttributes()
	{
		// NOTE: This XML is actually invalid, we're using it solely to make sure we're getting all possible attributes correctly!
		$xml = '<object abstract="true" class="My\Class\Object" name="myobj" parent="myparent" ref="myref" transient="true" />';

		$metadata = new ObjectMetadata();
		$parser = new ObjectMetadataParser();
		$element = new XmlElement($xml);
		$parser->parseAttributes($element);

		$parser->applyAttributes($metadata);

		$this->assertEquals('My\Class\Object', $metadata->getClassName());
		$this->assertEquals('myobj', $metadata->getName());
		$this->assertEquals('myparent', $metadata->getParentRef());
		$this->assertEquals('myref', $metadata->getRef('ref'));
		$this->assertTrue($metadata->isAbstract());
		$this->assertTrue($metadata->isTransient());
	}

	public function testApplyAttributeAbstract()
	{
		// NOTE: This XML is actually invalid, we're using it solely to make sure we're getting all possible attributes correctly!
		$xml = '<object abstract="true" />';

		$metadata = new ObjectMetadata();
		$parser = new ObjectMetadataParser();
		$element = new XmlElement($xml);

		$parser->parseAttributes($element);
		$parser->applyAttributes($metadata);

		$this->assertEquals(1, count($parser->getAttributes()));
		$this->assertEquals('true', $parser->getAttribute('abstract'));
		$this->assertTrue($metadata->isAbstract());
	}

	public function testGetAttributes()
	{
		// NOTE: This XML is actually invalid, we're using it solely to make sure we're getting all possible attributes correctly!
		$xml = '<object abstract="true" class="My\Class\Object" name="myobj" parent="myparent" ref="myref" transient="true" />';

		$parser = new ObjectMetadataParser();
		$element = new XmlElement($xml);
		$parser->parseAttributes($element);

		$attrs = $parser->getAttributes();
		$this->assertEquals(6, count($attrs));
		$this->assertEquals('true', $attrs['abstract']);
		$this->assertEquals('My\Class\Object', $attrs['class']);
		$this->assertEquals('myobj', $attrs['name']);
		$this->assertEquals('myparent', $attrs['parent']);
		$this->assertEquals('myref', $attrs['ref']);
		$this->assertEquals('true', $attrs['transient']);
	}

	public function testGetBooleanAttribute()
	{
		$parser = new ObjectMetadataParser();
		$parser->setAttributes(array('abstract' => 'true'));

		$actual = $parser->getBooleanAttribute('abstract');
		$this->assertEquals(1, count($parser->getAttributes()));
		$this->assertEquals('true', $parser->getAttribute('abstract'));
		$this->assertTrue($actual);
	}

	public function testParse()
	{
		$xml = <<<EOT
<object name="myobj" class="My\Class\Object" transient="true">
	<property name="greeting" value="hello" />
	<property name="subject" value="world" />
</object>
EOT;

		$parser = new ObjectMetadataParser();
		$element = new XmlElement($xml);
		$metadata = $parser->parse($element);

		$this->assertEquals('My\Class\Object', $metadata->getClassName());
		$this->assertEquals('myobj', $metadata->getName());
		$this->assertEquals(null, $metadata->getParentRef());
		$this->assertEquals(null, $metadata->getRef());
		$this->assertEquals(false, $metadata->isAbstract());
		$this->assertEquals(true, $metadata->isTransient());
	}

}