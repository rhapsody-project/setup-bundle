<?php
namespace Rhapsody\SetupBundle\Type;

class Boolean implements IType
{

	/**
	 *
	 * @param unknown $input
	 * @return Ambigous <boolean, string>|boolean
	 * @see Rhapsody\SetupBundle\Converters\ITypeConverter
	 */
	public function convert($input, array $attributes = array())
	{
		$booleans = array('true' => true, 'yes' => true, 'false' => false, 'no' => false);
		if (is_string($input)) {
			$input = strtolower(trim($input));
			return array_key_exists($input, $booleans) ? $booleans[$input] : false;
		}
		return is_bool($input) ? $input : false;
	}
}