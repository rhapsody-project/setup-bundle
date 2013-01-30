<?php
namespace Rhapsody\SetupBundle\Test\Xml\Parser;

use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Xml\XmlElement;
use Rhapsody\SetupBundle\Xml\Parser\QueryParser;

/**
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Test\Xml\Parser
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class QueryParserTest extends \PHPUnit_Framework_TestCase
{

	public function getLog()
	{
		return new Logger(get_class($this));
	}

	public function testAssert()
	{
		$xml = '<query name="queryobj" statement="the_statement" type="My\Test\Class" />';
		$element = new XmlElement($xml);

		$parser = new QueryParser();
		$parser->assert($element);
	}

	public function testAssertNoStatement()
	{
		$this->setExpectedException('InvalidArgumentException');

		$xml = '<object name="queryobj" type="My\Test\Class" />';
		$element = new XmlElement($xml);
		$parser = new QueryParser();
		$parser->assert($element);
	}

	public function testAssertNoType()
	{
		$this->setExpectedException('InvalidArgumentException');

		$xml = '<object name="queryobj" statement="the_statement" />';
		$element = new XmlElement($xml);
		$parser = new QueryParser();
		$parser->assert($element);
	}

	public function testAssertNull()
	{
		$this->setExpectedException('NullPointerException');

		$parser = new QueryParser();
		$parser->assert(null);
	}

	public function testApplyAttributes()
	{
		$xml = '<query name="myobj" statement="the_statement" type="My\Class\Object" projection="object" />';

		$query = new Query();
		$parser = new QueryParser();
		$element = new XmlElement($xml);
		$parser->parseAttributes($element);

		$parser->applyAttributes($query);

		$this->assertEquals('myobj', $query->getName());
		$this->assertEquals('the_statement', $query->getStatement());
		$this->assertEquals('My\Class\Object', $query->getType());
		$this->assertEquals('object', $query->getProjection());
	}

	public function testGetAttributes()
	{
		$xml = '<query name="myobj" statement="the_statement" type="My\Class\Object" projection="object" />';

		$parser = new QueryParser();
		$element = new XmlElement($xml);
		$parser->parseAttributes($element);

		$attrs = $parser->getAttributes();
		$this->assertEquals(4, count($attrs));

		$this->assertEquals('myobj', $attrs['name']);
		$this->assertEquals('object', $attrs['projection']);
		$this->assertEquals('the_statement', $attrs['statement']);
		$this->assertEquals('My\Class\Object', $attrs['type']);
	}

	public function testParse()
	{
		$xml = '<query name="myobj" statement="the_statement" type="My\Class\Object" projection="object" />';

		$parser = new QueryParser();
		$element = new XmlElement($xml);
		$query = $parser->parse($element);

		$this->assertEquals('myobj', $query->getName());
		$this->assertEquals('the_statement', $query->getStatement());
		$this->assertEquals('My\Class\Object', $query->getType());
		$this->assertEquals('object', $query->getProjection());
	}
}