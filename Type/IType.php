<?php
namespace Rhapsody\SetupBundle\Type;

interface IType
{

	/**
	 *
	 * @param mixed $input
	 * @param array $attributes
	 * @return mixed
	 */
	public function convert($input, array $attributes = array());
}