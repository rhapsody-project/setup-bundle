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