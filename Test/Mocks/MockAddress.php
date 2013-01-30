<?php
namespace Rhapsody\SetupBundle\Test\Mocks;

class MockAddress
{
	private $address1;
	private $address2;
	private $city;
	private $state;
	private $zipCode;

	public function getAddress1()
	{
		return $this->address1;
	}

	public function getAddress2()
	{
		return $this->address2;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function getState()
	{
		return $this->state;
	}

	public function getZipCode()
	{
		return $this->zipCode;
	}
}
