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
namespace Rhapsody\SetupBundle\Xml\Parser;

use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Parser\Parser;
use Rhapsody\SetupBundle\Xml\XmlElement;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Xml\Parser
 * @copyright Copyright (c) 2013 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class QueryParser extends Parser
{

	/**
	 *
	 */
	public static function parseQuery($xml)
	{
		$parser = new QueryParser();
		return $parser->parse($xml);
	}

	/**
	 * @param XmlElement $xml
	 * @see \Rhapsody\SetupBundle\Parser\Parser::assert()
	 */
	public function assert($xml)
	{
		if ($xml === null) {
			throw new \NullPointerException('Unable to parse: null.');
		}

		if (!($xml instanceof XmlElement)) {
			throw new \InvalidArgumentException('Invalid input. '.get_class($this).' expects an XmlElement');
		}

		$statement = $xml->getAttribute('statement');
		if (empty($statement)) {
			throw new \InvalidArgumentException('Invalid &lt;query>: No statement to execute.');
		}

		$type = $xml->getAttribute('type');
		if (empty($type)) {
			throw new \InvalidArgumentException('Unable to perform query: '.$statement.' on unknown type.');
		}
	}

	/**
	 * @return \Rhapsody\SetupBundle\Model\PropertyMetadata
	 * 		the metadata representing this object.
	 */
	public function parse($xml)
	{
		$this->assert($xml);
		$this->parseAttributes($xml);

		$query = new Query();
		$this->applyAttributes($query);
		return $query;
	}

	/**
	 *
	 * @param XmlElement $xml
	 */
	public function parseAttributes($xml)
	{
		$this->attributes = $xml->getAttributes();
		foreach ($this->attributes as $name => $value)
		{
			$key = strtolower(trim($name));
			$this->attributesMap[$key] = $name;
		}
	}

	public function applyAttributes(&$query)
	{
		$name = $this->getAttribute('name');
		if (!empty($name)) {
			$query->setName($name);
		}

		$projection = $this->getAttribute('projection');
		if (!empty($projection)) {
			$query->setProjection($projection);
		}

		$statement = $this->getAttribute('statement');
		if (!empty($query)) {
			$query->setStatement($statement);
		}

		$type = $this->getAttribute('type');
		if (!empty($type)) {
			$query->setType($type);
		}
	}
}