<?php
namespace Rhapsody\SetupBundle\Type;

class String implements IType
{

	/**
	 *
	 * @param unknown $value
	 * @return Ambigous <boolean, string>|boolean
	 * @see Rhapsody\SetupBundle\Converters\ITypeConverter
	 */
	public function convert($input, array $attributes = array())
	{
		return (string) strval(trim($input));
	}
}