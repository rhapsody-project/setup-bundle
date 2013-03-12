<?php
namespace Rhapsody\SetupBundle\Populator;

use Application\LorecallBundle\Document\Item\Item;
use Doctrine\ODM\MongoDB\DocumentManager;
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
class DoctrineMongoDatabasePopulator extends DatabasePopulator
{

	private $validatedManager = false;

	/**
	 * @param Doctrine\ODM\MongoDB\DocumentManager $documentManager
	 */
	private function _save(DocumentManager $documentManager)
	{
		try {
			if ($this->queue->count() <= 0) {
				$this->getLog()->debug('Queue contains no documents for processing.');
				return;
			}

			$this->getLog()->debug('Queue contains: '.$this->queue->count().' documents.');
			foreach ($this->queue as $object) {
				$name = $object->getName();
				$_id = $object->getId();

				if (empty($_id)) {
					$document = $object->getInstance();

					$this->getLog()->info('Saving document: '.$name.' ('.$document->__toString().')');
					$documentManager->persist($document);
					$documentManager->flush();

					$documentId = $document->getId();
					if (!empty($name) && !empty($documentId)) {
						$this->getLog()->info('Marking document: '.$name.' ('.get_class($document).') as persisted in cache with ID: '.$documentId);
						$this->markAsPersisted($name, $documentId, $document);
					}
				}
			}
			$this->queue->clear();
			$this->getLog()->debug('Finished saving documents, queue now contains: '.$this->queue->count().' documents.');
		}
		catch (\Exception $ex) {
			$this->getLog()->err('An error occurred while attempting to save one or more documents. '.get_class($ex).' handled, with message: '.$ex->getMessage());
			$documentManager->close();
			throw $ex;
		}
	}

	public function clean()
	{
		//$dm = $this->getDocumentManager();
	}

	protected function finalize()
	{
		$dm = $this->getDocumentManager();
		//$dm->clear();
		$dm->flush();
	}

	/**
	 * @param \Rhapsody\SetupBundle\Model\Query
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::query()
	 */
	public function query(Query $query)
	{
		$dm = $this->getDocumentManager();

		$json = preg_replace("/[']+/i", '"', $query->getStatement());
		$statement = json_decode($json, true);
		$class = $query->getTypeAsClassName();

		return $dm->getRepository($class)->findOneBy($statement);
	}

	/**
	 * @return Doctrine\ODM\MongoDB\DocumentManager
	 */
	protected function getDocumentManager()
	{
		$manager = $this->getDatabaseManager()->getManager();
		if ($this->validatedManager === false) {
			if (!($manager instanceof DocumentManager)) {
				throw new \Exception('The manager: '.get_class($manager).' is not a valid Doctrine MongoDB ODM DocumentManager. '
						.'Please check your configuration and make sure that you are using the doctrine_mongodb service for populator: '
						.get_class($this));
			}
			else {
				$this->validatedManager = true;
			}
		}
		return $manager;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Populator\IPopulator::save()
	 */
	public function save()
	{
		$this->getLog()->debug('START: save()');
		$dm = $this->getDocumentManager();
		$this->_save($dm);
		$this->getLog()->debug('END: save()');
	}

}