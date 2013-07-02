<?php
namespace Rhapsody\SetupBundle\Model;

/**
 *
 * @author Sean.Quinn
 * @since 1.0
 */
interface FieldInterface
{

	/**
	 *
	 * @param mixed $object
	 * @throws \OutOfBoundsException
	 * @return mixed
	 */
	function resolve($object);
}