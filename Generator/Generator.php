<?php
namespace Rhapsody\SetupBundle\Generator;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bridge\Monolog\Logger;

/**
 * <p>
 * A <code>Generator</code> is an abstract implementation of the
 * <code>GeneratorInterface</code> that renders access to a database manager
 * and helps to facilitate the the creation of complex objects in code, rather
 * than <tt>XML</tt>.
 * </p>
 *
 * @author Sean.Quinn
 * @since 1.0
 */
abstract class Generator implements GeneratorInterface
{

	/**
	 * The character class.
	 * @var string
	 * @access protected
	 */
	protected $class;

	/**
	 * The document manager that can be used to retrieve repositories and
	 * perform database operations.
	 * @var Doctrine\Common\Persistence\ManagerRegistry
	 * @access protected
	 */
	protected $databaseManager;

	/**
	 * The logger interface.
	 * @var LoggerInterface
	 * @access protected
	 */
	protected $log = null;

	public function __construct($class, ManagerRegistry $databaseManager)
	{
		$this->class = $class;
		$this->databaseManager = $databaseManager;
		$this->log = new Logger(get_class($this));
		$this->__init();
	}

	protected function __init()
	{
		// Empty.
	}

	/**
	 * Retrieves a value, by key name, from an associative array of arguments.
	 *
	 * @param array $args the <tt>array</tt> of arguments.
	 * @param mixed $key the key.
	 * @param mixed $default Optional. The default value to return if the key
	 * 		does not point to a valid entry, <tt>null</tt> by default.
	 * @return mixed
	 */
	protected function getArg($args, $key, $default = null)
	{
		if (array_key_exists($key, $args)) {
			return $args[$key];
		}
		return $default;
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

	protected function hasArg($args, $key)
	{
		return array_key_exists($key, $args);
	}

	/**
	 * @return \Doctrine\Common\Persistence\ManagerRegistry
	 */
	protected function getDatabaseManager()
	{
		return $this->databaseManager;
	}
}