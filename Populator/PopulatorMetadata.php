<?php
namespace Rhapsody\SetupBundle\Populator;

/*

	<populator>
		<metadata>...</metadata>
		...
	</populator>

	populator:
		metadata:
			...
		data:
			properties:
				...
		data:
			properties:
				...
				embedded:
					object:
						type: ""
						properties:
							...
 */

/**
 *
 *
 * @author Sean.Quinn
 */
class PopulatorMetadata
{
	/**
	 * The database connection to use; overrides the default database connection
	 * specified in the populator configuration, but is itself overridden by
	 * a command line argument's database.
	 * @var unknown
	 */
	private $database;

	/**
	 * The object's type; this is the fully qualified name of the class that
	 * will be built up for each data element, if a type is not explicitly
	 * declared on the data element itself.
	 * @var string
	 */
	private $type;

	public function getDatabase()
	{
		return $this->database;
	}

	public function getType()
	{
		return $this->type;
	}

	public function setDatabase($database)
	{
		$this->database = $database;
	}

	public function setType($type)
	{
		$this->type = $type;
	}
}