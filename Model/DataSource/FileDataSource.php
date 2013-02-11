<?php
namespace Rhapsody\SetupBundle\Model\DataSource;

use Symfony\Bridge\Monolog\Logger;

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
abstract class FileDataSource extends AbstractDataSource
{
	/**
	 * The file containing the data to be read, and transformed into objects,
	 * by the populator.
	 * @var string
	 */
	protected $file;

	public function __construct($file)
	{
		if (!is_file($file)) {
			throw new \Exception('The file: '.$file.' is not a valid file.');
		}
		$this->file = $file;
		$this->log = new Logger(get_class($this));
	}

	/**
	 * <p>
	 * Returns the source file for this <tt>DataSource</tt>.
	 * </p>
	 *
	 * @return string the file name.
	 */
	public function getFile()
	{
		return $this->file;
	}
}