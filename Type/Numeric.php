<?php
namespace Rhapsody\SetupBundle\Type;

class Numeric implements IType
{

	/**
	 *
	 * @param unknown $value
	 * @return Ambigous <boolean, string>|boolean
	 * @see Rhapsody\SetupBundle\Converters\ITypeConverter
	 */
	public function convert($input, array $attributes = array())
	{
		$value = trim($input);
		if (is_numeric($input)) {
			return $input + 0;
		}
		return -1;
	}
}