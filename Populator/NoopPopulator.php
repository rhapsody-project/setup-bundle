<?php
namespace Rhapsody\SetupBundle\Populator;

use Rhapsody\SetupBundle\Cache\DataObjectCache;
use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Queue\DataObjectQueue;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Populator
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class NoopPopulator extends AbstractPopulator
{

	private $queryResult = null;

	// TODO: Add a member that can be returned as part of a query

	public function __construct()
	{
		$this->cache = new DataObjectCache();
		$this->queue = new DataObjectQueue();
	}

	public function clean()
	{
		// Empty
	}

	public function mockQueryResult($object)
	{
		$this->queryResult = $object;
		return $this;
	}

	public function query(Query $query)
	{
		if ($this->queryResult !== null) {
			return $this->queryResult;
		}
		return null;
	}

	/**
	 *
	*/
	public function run()
	{
		// No-op.
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::save()
	 */
	public function save()
	{
		// No-op.
	}
}