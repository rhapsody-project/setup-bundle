<?php
namespace Rhapsody\SetupBundle\Test\Mocks;

class MockPerson
{
	private $name;
	private $address;

	public function getName()
	{
		return $this->name;
	}

	public function getAddress()
	{
		return $this->address;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
}
