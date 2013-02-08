<?php
namespace Rhapsody\SetupBundle\Populator;

use Symfony\Bridge\Monolog\Logger;

use Doctrine\Common\Persistence\ManagerRegistry;
use Rhapsody\SetupBundle\Cache\DataObjectCache;
use Rhapsody\SetupBundle\Converters\ConverterFactory;
use Rhapsody\SetupBundle\Xml\XmlDataSource;
use Rhapsody\SetupBundle\Xml\XmlElement;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

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
abstract class AbstractPopulator implements ContainerAwareInterface, IPopulator
{

	/**
	 * The <tt>DataObjectCache</tt>. This cache contains all data objects
	 * identified with an <tt>id</tt>, allowing for object references to lookup
	 * an already identified object from the cache.
	 *
	 * @var Rhapsody\SetupBundle\Cache\DataObjectCache
	 */
	protected $cache;

	/**
	 * The container.
	 * @var Symfony\Component\DependencyInjection\ContainerAwareInterface;
	 */
	protected $container;

	/**
	 * An array of data sources.
	 * @var array
	 */
	protected $dataSources = array();

	/**
	 *
	 * @var array
	 */
	protected $files = array();

	/**
	 *
	 * @var unknown
	 */
	protected $log = null;

	/**
	 *
	 * @var Rhapsody\SetupBundle\Queue\DataObjectQueue
	 */
	protected $queue;

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::getCache()
	 */
	public function getCache()
	{
		if (!isset($this->cache)) {
			throw new \NullPointerException('Populator cache for: '.get_class($this).' is not set.');
		}
		return $this->cache;
	}

	/**
	 * <p>
	 * Returns the service container.
	 * </p>
	 *
	 * @return ContainerInterface the container.
	 */
	protected function getContainer()
	{
		return $this->container;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::getDataSources()
	 */
	public function getDataSources()
	{
		return $this->dataSources;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::getFiles()
	 */
	public function getFiles()
	{
		return $this->files;
	}

	/**
	 * @return LoggerInterface
	 */
	protected function getLog()
	{
		if ($this->log === null) {
			$this->log = new Logger(get_class($this));
		}
		return $this->log;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::getQueue()
	 */
	public function getQueue()
	{
		if (!isset($this->queue)) {
			throw new \NullPointerException('Populator queue for: '.get_class($this).' is not set.');
		}
		return $this->queue;
	}

	/**
	 * <p>
	 * Initializes the populator.
	 * </p>
	 */
	protected function initialize()
	{
		// Empty.
	}

	/**
	 * <p>
	 * Looks up a <tt>DataObject</tt> by its <tt>$id</tt> from either the cache
	 * or one of the other data sources (if there are more than one). Each data
	 * source has an index of all identified objects, so it is fairly straight
	 * forward to determine if a data source contains a particular object and
	 * retrieve that object if it does.
	 * </p>
	 *
	 * @param string $id the identifier of the object.
	 * @return DataObject|null the <tt>DataObject</tt>, if found; otherwise
	 * 		<tt>null</tt>.
	 */
	public function lookup($id)
	{
		// TODO[#object-flush]: Before returning an object, whether it is in the
		//		 cache or being read proactively from another datasource, if it
		//		 is also in the queue we should be flushed before we return the
		//		 object so that we can properly reference the object
		if ($this->cache->has($id)) {
			$this->getLog()->debug('Found object with ID: '.$id.' in cache.');
			// TODO: See above todo note #object-flush.
			return $this->cache->get($id);
		}

		// ** The data sources are empty, there is nothing we can do.
		if (empty($this->dataSources)) {
			$this->getLog()->debug('Data sources are empty, aborting lookup for: '.$id);
			return null;
		}

		foreach ($this->dataSources as $dataSource)
		{
			$dsn = $dataSource->getName();
			$this->getLog()->debug('Looking for: '.$id.' in data source: '.$dsn);
			if (!$dataSource->isPrepared()) {
				$dataSource-prepare();
			}

			if ($dataSource->contains($id)) {
				$this->getLog()->debug('Found object with ID: '.$id.' in data source: '.$dsn);
				$object = $dataSource->findObject($id);
				if ($object === null) {
					// **
					// We're not taking anything for granted, if the data source
					// claims to have it, but we can't find it then we throw an
					// exception because something is clearly not right here. [SWQ]
					throw new \Exception('The data source: '.$dsn
							.' reported that it contained the object: '.$id
							.' however the populator failed to read it.');
				}
				// TODO: See above todo note #object-flush.
				return $object;
			}
		}
		return null;
	}

	public function run()
	{
		if ($this->getContainer() === null) {
			throw new \NullPointerException('Service container is null; this populator requires access to the service container for logging');
		}

		if (!is_array($this->dataSources)) {
			$this->dataSources = array();
		}

		foreach ($this->files as $file) {
			$this->getLog()->debug('Adding data source: '.$file.' to data sources');
			$dataSource = new XmlDataSource($file);
			array_push($this->dataSources, $dataSource);
		}

		foreach ($this->dataSources as $dataSource) {
			$this->getLog()->notice('Preparing data source: '.$dataSource->getName());
			$dataSource->setPopulator($this);
			$dataSource->prepare();
		}

		foreach ($this->dataSources as $dataSource) {
			$this->getLog()->notice('Processing data source: '.$dataSource->getName());
			$dataSource->process();
		}
	}

	/**
	 * (non-PHPdoc)
	 * @see \Symfony\Component\DependencyInjection\ContainerAwareInterface::setContainer()
	 */
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}

	public function setDataSources(array $dataSources = array())
	{
		$this->dataSources = $dataSources;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::setFiles()
	 */
	public function setFiles(array $files = array())
	{
		$this->files = $files;
	}
}