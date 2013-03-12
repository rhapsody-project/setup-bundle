<?php
namespace Rhapsody\SetupBundle\Populator;

use Doctrine\Common\Persistence\ManagerRegistry;
use Rhapsody\SetupBundle\Cache\DataObjectCache;
use Rhapsody\SetupBundle\Converters\ConverterFactory;
use Rhapsody\SetupBundle\Queue\DataObjectQueue;
use Rhapsody\SetupBundle\Xml\XmlElement;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
abstract class DatabasePopulator extends AbstractPopulator
{
	/**
	 *
	 * @var unknown
	 */
	protected $batchSize = 1;

	/**
	 *
	 * @var Doctrine\Common\Persistence\ManagerRegistry
	 */
	protected $databaseManager;

	/**
	 *
	 * @param ContainerInterface $container
	 * @param ManagerRegistry $databaseManager
	 * @param array $files
	 */
	public function __construct(ContainerInterface $container, ManagerRegistry $databaseManager, array $dataSources = array(), array $files = array())
	{
		$this->cache = new DataObjectCache();
		$this->queue = new DataObjectQueue();
		$this->container = $container;
		$this->databaseManager = $databaseManager;
		$this->dataSources = $dataSources;
		$this->files = $files;

		$this->initialize();
	}

	abstract protected function finalize();

	public function getBatchSize()
	{
		return $this->batchSize;
	}

	/**
	 * <p>
	 * </p>
	 * @return Doctrine\Common\Persistence\ManagerRegistry
	 */
	public function getDatabaseManager()
	{
		return $this->databaseManager;
	}

	public function run()
	{
		parent::run();
		$this->finalize();
	}

	public function setBatchSize($batchSize)
	{
		$this->batchSize = $batchSize;
	}

	protected function markAsPersisted($id, $databaseId, $object)
	{
		$this->cache->updateInstance($id, $databaseId, $object);
	}

	/**
	 *
	 * @param ManagerRegistry $databaseManager
	 */
	public function setDatabaseManager(ManagerRegistry $databaseManager)
	{
		$this->databaseManager = $databaseManager;
	}
}