<?php
namespace Rhapsody\SetupBundle\Generator;

use Doctrine\ODM\MongoDB\DocumentManager;

/**
 * <p>
 * </p>
 *
 * @author Sean.Quinn
 * @since 1.0
 */
abstract class MongoDbGenerator extends Generator
{
	/**
	 * @return Doctrine\ODM\MongoDB\DocumentManager
	 */
	protected function getDocumentManager()
	{
		$manager = $this->getDatabaseManager()->getManager();
		if (!($manager instanceof DocumentManager)) {
			throw new \Exception('The manager: '.get_class($manager).' is not a valid Doctrine MongoDB ODM DocumentManager. '
					.'Please check your configuration and make sure that you are using the doctrine_mongodb service for populator: '
					.get_class($this));
		}
		return $manager;
	}
}