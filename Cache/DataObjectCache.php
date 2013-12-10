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
namespace Rhapsody\SetupBundle\Cache;

use Rhapsody\SetupBundle\Model\Object;

/**
 *
 * @author Sean.Quinn
 *
 */
class DataObjectCache
{
	private $cache = array();

	// contains an associative array of ids => populator objects.
	// strict prevents one id from overriding another, if the id already exists an error is thrown.

	/**
	 *
	 * @param unknown $id
	 * @param Rhapsody\SetupBundle\Model\Object $object
	 */
	public function add($id, $object)
	{
		$_id = strtolower(trim($id));
		if (array_key_exists($_id, $this->cache)) {
			throw new \Exception('Unable to add object: '.$object->getClassName().' with ID: '.$id
					.'. An object already exists in the cache with that identifier.');
		}
		$this->cache[$_id] = $object;
	}

	public function count()
	{
		return count($this->cache);
	}

	public function get($id)
	{
		$_id = strtolower(trim($id));
		if (array_key_exists($_id, $this->cache)) {
			return $this->cache[$_id];
		}
		return null;
	}

	public function getInstance($id)
	{
		return $this->get($id)->getInstance();
	}

	public function has($id)
	{
		$_id = strtolower(trim($id));
		return array_key_exists($_id, $this->cache);
	}

	public function updateInstance($id, $databaseId, $instance)
	{
		$object = $this->get($id);
		if ($object === null) {
			throw new \NullPointerException('Could not update the instance for: '.$id.', no object found in the cache with that ID.');
		}
		$object->setId($databaseId);
		$object->setInstance($instance);
	}
}