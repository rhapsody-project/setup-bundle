<?php
namespace Rhapsody\SetupBundle\Populator;

use Doctrine\ORM\EntityManager;
use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Populator\DatabasePopulator;

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
class DoctrineDatabasePopulator extends DatabasePopulator
{

	/**
	 * @param Doctrine\ORM\EntityManager $entityManager
	 */
	private function _save(EntityManager $entityManager)
	{
		$entityManager->transactional(function($em) {
			foreach ($this->queue as $entity) {
				$entityManager->persist($entity);
			}
		});
	}

	public function query(Query $query)
	{

	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::save()
	 */
	public function save()
	{
		if (count($this->queue) < $this->batchSize) {
			return;
		}

		$manager = $this->getDatabaseManager()->getManager();
		if (!($manager instanceof Doctrine\ORM\EntityManager)) {
			throw new \Exception('The manager: '.get_class($manager).' is not a valid Doctrine ORM EntityManager.');
		}
		$this->_save($manager);
	}
}