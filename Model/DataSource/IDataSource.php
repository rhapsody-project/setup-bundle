<?php
namespace Rhapsody\SetupBundle\Model\DataSource;

interface IDataSource
{

	/**
	 * <p>
	 * Returns the <tt>DataObjectCache</tt> for this populator.
	 * </p>
	 *
	 * @return \Rhapsody\SetupBundle\Cache\DataObjectCache
	 * 		the <tt>DataObjectCache</tt> for this populator.
	 */
	public function cache($id, $object);

	/**
	 * <p>
	 * Finds an object within the data source and parses it, returning a
	 * <tt>DataObject</tt>.
	 * </p>
	 *
	 * @param string $id the identifier of the object.
	 * @return Rhapsody\SetupBundle\Populator\DataObject
	 * 		the found <tt>DataObject</tt>, or null.
	 */
	public function findObject($id);

	public function getName();

	public function prepare();

	/**
	 *
	 */
	public function process();

	public function queue($object);

}