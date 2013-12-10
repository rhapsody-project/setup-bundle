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
namespace Rhapsody\SetupBundle\Model\DataSource;

use Rhapsody\SetupBundle\Model\Object;
use Rhapsody\SetupBundle\Model\Query;
use Rhapsody\SetupBundle\Populator\IPopulator;
use Symfony\Bridge\Monolog\Logger;

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
abstract class AbstractDataSource implements IDataSource
{

	private static $scalar = array('int', 'string', 'boolean');

	/**
	 * An index of object identifiers, compiled at the time the data source is
	 * prepared.
	 * @var array
	 */
	protected $index = array();

	/**
	 *
	 * @var unknown
	 */
	protected $log = null;

	/**
	 * The populator reference.
	 * @var Rhapsody\SetupBundle\Populator\IPopulator
	 */
	protected $populator;

	/**
	 * Whether the data source has been prepared or not.
	 * @property boolean
	 * @access protected
	 */
	protected $prepared = false;

	/**
	 * <p>
	 * Caches a <tt>DataObject</tt> in the reference populator's cache.
	 * </p>
	 *
	 * @param DataObject $object
	 * @see Rhapsody\SetupBundle\Data\IDataSource::cache($object)
	 */
	public function cache($id, $object)
	{
		$this->populator->getCache()->add($id, $object);
	}

	/**
	 *
	 * @param unknown $id
	 * @return boolean
	 */
	public function contains($id)
	{
		$_id = strtolower(trim($id));
		return in_array($_id, $this->index);
	}

	/**
	 *
	 * @param unknown $id
	 */
	protected function get($name)
	{
		return $this->populator->getCache()->get($name);
	}

	public function getIndex()
	{
		return $this->index;
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

	public function getName()
	{
		return get_called_class();
	}

	protected function has($id)
	{
		return $this->populator->getCache()->has($id);
	}

	/**
	 *
	 * @param \Rhapsody\SetupBundle\Model\Query $query
	 * @throws \NullPointerException
	 * @return unknown
	 */
	public function query(Query $query)
	{
		$result = $this->populator->query($query);
		if ($result === null) {
			throw new \NullPointerException('Unable to find object: '.$query->getType().' using query:'.$query->getStatement());
		}

		if ($query->hasName()) {
			$name = $query->getName();
			$object = Object::fromInstance($name, $result);
			$this->cache($name, $object);
		}
		return $result;
	}

	/**
	 * <p>
	 * Adds an object to thisCaches a <tt>DataObject</tt> in the reference populator's cache.
	 * </p>
	 *
	 * @param DataObject $object
	 * @see Rhapsody\SetupBundle\Data\IDataSource::cache($object)
	 */
	public function queue($object)
	{
		$this->getLog()->notice('Queuing object for persistence: '.$object->__toString());
		$this->populator->getQueue()->add($object);
	}

	/**
	 * <p>
	 * Attempts to resolve a reference to an identified object, by the passed
	 * <tt>$id</tt> from the internal data source's index of objects first. If
	 * this data source does not contain the object reference, the data source
	 * will fall back to the populator in an attempt to lookup the object from
	 * other data sources.
	 * </p>
	 *
	 * @param string $name the name identifying the object to look up.
	 * @throws \Exception
	 */
	public function getReference($name)
	{
		$ref = null;

		// ** Look for the object first within this data sources.
		if ($this->contains($name)) {
			$ref = $this->findObject($name);
			if ($ref !== null) {
				return $ref;
			}
		}

		// ** Look for the object within other data sources.
		$ref = $this->populator->lookup($name);
		if ($ref === null) {
			$this->getLog()->debug('Unable to find a object by reference: '.$name.'. Please check your data sources. '
				.'If the object has already been persisted by another populator use the "query" attribute instead of "ref".');
			throw new \NullPointerException('Unable to find reference: '.$name.'. Does this object exist in your included data sources?');
		}
		return $ref;
	}

	public function isPrepared()
	{
		return $this->prepared;
	}

	/**
	 *
	 */
	public function process()
	{
		$this->prepare();
		$this->handle();
	}

	/**
	 *
	 */
	abstract protected function handle();

	/**
	 *
	 * @param IPopulator $populator
	 */
	public function setPopulator(IPopulator $populator)
	{
		$this->populator = $populator;
	}
}