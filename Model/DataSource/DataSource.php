<?php
/* Copyright 2012 Extesla Digital Entertainment, Ltd.. All rights reserved.
 *
 * It is illegal to use, reproduce or distribute any part of this
 * Intellectual Property without prior written authorization from
 * Extesla Digital Entertainment, Ltd..
 */
namespace Rhapsody\SetupBundle\Model\DataSource;

use Rhapsody\SetupBundle\Model\DataRecordInterface;
use Rhapsody\SetupBundle\Model\Object;
use Rhapsody\SetupBundle\Model\ObjectMetadata;

/**
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Model\DataSource
 * @copyright Copyright (c) 2013 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class DataSource extends AbstractDataSource
{

	/**
	 * The collection of objects that have been registered with this data
	 * source.
	 * @var array
	 * @access protected
	 */
	private $data = array();

	/**
	 * Adds an object to the data source.
	 * @param unknown $object
	 */
	public function add(DataRecordInterface $record)
	{
		$this->data[] = $record;
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Model\DataSource\IDataSource::findObject()
	 */
	public function findObject($id)
	{
		throw new \Exception();
	}

	/**
	 * (non-PHPDoc)
	 * @see Rhapsody\SetupBundle\Model\DataSource\AbstractDataSource::process()
	 */
	protected function handle()
	{
		foreach ($this->data as $record) {
			$instance = $record->instantiate();

			$metadata = new ObjectMetadata();
			$metadata->setClassName(get_class($instance));

			$object = new Object($metadata);
			$object->setInstance($instance);
			$this->queue($object);
		}
		$this->populator->save();
	}

	/**
	 * (non-PHPdoc)
	 * @see \Rhapsody\SetupBundle\Model\DataSource\IDataSource::prepare()
	 */
	public function prepare()
	{
		$this->prepared = true;
	}
}