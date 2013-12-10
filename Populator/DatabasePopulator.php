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
 * @copyright Copyright (c) 2013 Rhapsody Project
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