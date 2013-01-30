<?php
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