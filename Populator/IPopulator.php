<?php
namespace Rhapsody\SetupBundle\Populator;

use Rhapsody\SetupBundle\Model\Query;

interface IPopulator
{

	/**
	 * Cleans the destination target(s) for this populator.
	 */
	function clean();

	/**
	 * <p>
	 * Returns the <tt>DataObjectCache</tt> for this populator.
	 * </p>
	 *
	 * @return \Rhapsody\SetupBundle\Cache\DataObjectCache
	 * 		the <tt>DataObjectCache</tt> for this populator.
	 */
	function getCache();

	/**
	 *
	 */
	function getDataSources();

	/**
	 *
	 */
	function getFiles();

	/**
	 * @return \Rhapsody\SetupBundle\Queue\DataObjectQueue
	 */
	function getQueue();

	/**
	 *
	 * @param Query $query
	 */
	function query(Query $query);

	/**
	 *
	 */
	function run();

	/**
	 *
	 * @param unknown $entity
	 */
	function save();

	/**
	 *
	 * @param array $files
	 */
	function setFiles(array $files = array());
}