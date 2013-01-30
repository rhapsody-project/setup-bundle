<?php
namespace Rhapsody\SetupBundle\Command;

use \RuntimeException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Process\Process;
use Rhapsody\SetupBundle\Populator\IPopulator;

/**
 * <p>
 * </p>
 *
 * @author    Sean W. Quinn <sean.quinn@extesla.com>
 * @category  Rhapsody SetupBundle
 * @package   Rhapsody\SetupBundle\Command
 * @copyright Copyright (c) 2012 Rhapsody Project
 * @version   $Id$
 * @since     1.0
 */
class PopulateCommand extends ContainerAwareCommand
{

	/**
	 * @see Command
	 */
	protected function configure()
	{
		$this
		->setName('rhapsody:setup:populate')
		->setDefinition(array(
				new InputOption('populator', '-p', InputOption::VALUE_REQUIRED | InputOption::VALUE_IS_ARRAY, 'The populator class to run.'),
				new InputOption('clean', '-c', InputOption::VALUE_OPTIONAL, 'Clean the database before populating data into it. By default populators run in development and test modes will always clean the database.'),
				new InputOption('file', '-f', InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The files to use as data sources when running this populator.'),
				new InputOption('database', '-d', InputOption::VALUE_OPTIONAL, 'Override the database connection to be used.'),
				//new InputOption('config', '-c', InputOption::VALUE_OPTIONAL, 'Overrides the configuration values to use with the populator.'),
		))
		->setDescription('Populates data into the database.')
		->setHelp(<<<EOF
The <info>rhapsody:populate</info> command runs the designated populator;
optionally an override to the configured database may be specified.
EOF
		);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$environment = $input->getOption('env');

		try {
			$populators = $input->getOption('populator');
			foreach ($populators as $populator)
			{
				$output->writeln('Begin data population using: '.$populator);

				try {
					$populator = $this->getContainer()->get($populator);
					$this->processOverrides($populator, $input, $output);
				}
				catch (\ServiceNotFoundException $ex) {
					throw $ex;
					$pattern = "/[\\/\\\\]+/i";
					if (!preg_match($pattern, $populator)) {
						throw $ex;
					}

					$files = $this->getFilesOverride($input, $output);
					$database = $this->getDatabaseOverride($input, $output);
					$populator = $this->newPopulator($populator, $files, $database);
				}

				// ** Assert the populator is an IPopulator
				if (!($populator instanceof IPopulator)) {
					throw new \Exception('Populator must be an instance of IPopulator, not: '.gettype($populator));
				}

				$clean = $this->getCleanOption($input, $output);
				if ($clean === true) {
					$output->writeln('Populate command does not currently support cleaning the target database(s). '
							.'Please run <comment>doctrine:schema:drop</comment> or <comment>doctrine:mongodb:schema:drop</comment> instead.');
					//$output->writeln('Cleaning data source target(s) for: '.get_class($populator));
					//$populator->clean();
				}

				$output->writeln('Running populator: '.get_class($populator));
				$populator->run();
			}
		}
		catch (\Exception $ex) {
			$exception = new OutputFormatterStyle('red');
			$output->getFormatter()->setStyle('exception', $exception);

			$output->writeln("\n\n");
			$output->writeln('<exception>[Exception in: '.get_class($this).']</exception>');
			$output->writeln('<exception>Exception: '.get_class($ex).' with message: '.$ex->getMessage().'</exception>');
			$output->writeln('<exception>Stack Trace:</exception>');
			$output->writeln('<exception>'.$ex->getTraceAsString().'</exception>');
			exit(1);
		}
		exit(0);
	}

	private function newPopulator($className, $files, $database)
	{
		$pattern = "/[\.\\/\\\\]+/i";
		$className = preg_replace($pattern, "\\", $className);
		$class = new \ReflectionClass($className);

		$populator = $class->newInstance($files, $database);
		return $populator;
	}

	private function getCleanOption(InputInterface $input, OutputInterface $output)
	{
		$environment = $input->getOption('env');
		$clean = $input->getOption('clean');
		if ($clean === null && in_array($environment, array('dev', 'test'))) {
			return true;
		}
		return is_bool($clean) ? $clean : in_array(strtolower(trim($clean)), array('true', 'yes', '1'));
	}

	/**
	 * <p>
	 * Returns the database manager override, from the <tt>database</tt> input
	 * option.
	 * </p>
	 *
	 * @param InputInterface $input the input interface.
	 * @param OutputInterface $output the output interface.
	 * @return object the database manager.
	 * @throws ServiceNotFoundException if the database manager specified by the
	 * 		<tt>database</tt> option cannot be found.
	 */
	private function getDatabaseOverride(InputInterface $input, OutputInterface $output)
	{
		$database = $input->getOption('database');
		if ($output->getVerbosity() == OutputInterface::VERBOSITY_VERBOSE) {
			$option->writeln('Looking up database manager service: '.$database);
		}
		$database = $this->getContainer()->get($database);
		return $database;
	}

	/**
	 *
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @return unknown|NULL
	 */
	private function getFilesOverride(InputInterface $input, OutputInterface $output)
	{
		$files = $input->getOption('files');
		if (!empty($files) && is_array($files)) {
			return $files;
		}
		return null;
	}

	/**
	 *
	 * @param IPopulator $populator
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 */
	private function overrideFiles(IPopulator $populator, InputInterface $input, OutputInterface $output)
	{
		$option->writeln('Overriding populator files...');

		$files = $this->getFilesOverride($input, $output);
		if ($files !== null) {
			if ($output->getVerbosity() == OutputInterface::VERBOSITY_VERBOSE) {
				$option->writeln('Setting populator data source files to: '.print_r($files));
			}
			$populator->setFiles($files);
		}
	}

	/**
	 *
	 * @param IPopulator $populator
	 * @param InputInterface $input
	 * @param OutputInterface $output
	 * @throws \InvalidArgumentException
	 */
	private function overrideDatabase(IPopulator $populator, InputInterface $input, OutputInterface $output)
	{
		if (!($populator instanceof DatabasePopulator)) {
			throw new \InvalidArgumentException('Unable to override populator: '.get_class($populator).' database manager. Not a DatabasePopulator.');
		}

		$database = $this->getDatabaseOverride($input, $output);
		if ($database !== null) {
			if ($output->getVerbosity() == OutputInterface::VERBOSITY_VERBOSE) {
				$option->writeln('Overriding populator database manager to use: '.$input->getOption('database'));
			}
			$populator->setDatabaseManager($database);
		}
	}

	private function processOverrides(IPopulator $populator, InputInterface $input, OutputInterface $output)
	{
		$files = $input->getOption('file');
		if (!empty($file)) {
			$this->overrideFiles($populator, $input, $output);
		}

		// ** If a database option has
		$database = $input->getOption('database');
		if ($database !== null) {
			$this->overrideDatabase($populator, $input, $output);
		}
	}
}
