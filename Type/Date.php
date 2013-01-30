<?php
namespace Rhapsody\SetupBundle\Type;

class Date implements IType
{

	private $defaultFormat = 'U';

	/**
	 *
	 * @param unknown $input
	 * @param array $attributes
	 * @return Ambigous <boolean, string>|boolean
	 * @see Rhapsody\SetupBundle\Converters\ITypeConverter
	 */
	public function convert($input, array $attributes = array())
	{
		// ** Assume any integer value passed in is already a valid timestamp and just kick it back...
		if (is_numeric($input)) {
			return intval($input);
		}

		$format = array_key_exists('format', $attributes) ? $attributes['format'] : $this->defaultFormat;
		$dateTime = new \DateTime($input);
		$dateTime->format($format);
		return $dateTime->getTimestamp();
	}
}