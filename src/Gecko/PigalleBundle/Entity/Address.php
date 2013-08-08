<?php

namespace Gecko\PigalleBundle\Entity;

use Sylius\Bundle\AddressingBundle\Model\Address as BaseAddress;

/**
 * Address entity.
 *
 */
class Address extends BaseAddress
{
	/**
	 * Floor.
	 *
	 * @var string
	 */
	protected $floor;
	
	/**
	 * Department.
	 *
	 * @var string
	 */
	protected $department;
	
	
	public function __construct()
	{
		return parent::__construct();
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getFloor()
	{
		return $this->floor;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setFloor($floor)
	{
		$this->floor = $floor;
	
		return $this;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getDepartment()
	{
		return $this->department;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setDepartment($department)
	{
		$this->department = $department;
	
		return $this;
	}
}
