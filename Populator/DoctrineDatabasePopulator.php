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
 * @copyright Copyright (c) 2013 Rhapsody Project
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