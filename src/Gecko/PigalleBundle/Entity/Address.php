<?php

namespace Gecko\PigalleBundle\Entity;

use Sylius\Bundle\AddressingBundle\Model\AddressInterface;
use Sylius\Bundle\AddressingBundle\Model\CountryInterface;
use Sylius\Bundle\AddressingBundle\Model\ProvinceInterface;

/**
 * Address entity.
 *
 */
class Address implements AddressInterface
{
	/**
	 * Addres id.
	 *
	 * @var mixed
	 */
	protected $id;
	
	/**
	 * First name.
	 *
	 * @var string
	 */
	protected $firstName;
	
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
	
	/**
	 * Country.
	 *
	 * @var CountryInterface
	 */
	protected $country;
	
	/**
	 * Province.
	 *
	 * @var ProvinceInterface
	 */
	protected $province;
	
	/**
	 * Street.
	 *
	 * @var string
	 */
	protected $street;
	
	/**
	 * City.
	 *
	 * @var string
	 */
	protected $city;
	
	/**
	 * Postcode.
	 *
	 * @var string
	 */
	protected $postcode;
	
	/**
	 * Creation time.
	 *
	 * @var DateTime
	 */
	protected $createdAt;
	
	/**
	 * Update time.
	 *
	 * @var DateTime
	 */
	protected $updatedAt;
	
	
	public function __construct()
	{
		$this->createdAt = new \DateTime('now');
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getId()
	{
		return $this->id;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getFirstName()
	{
		return $this->firstName;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	
		return $this;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getLastName()
	{
		return $this->lastName;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setLastName($lastName)
	{
		$this->lastName = $lastName;
	
		return $this;
	}
	
	public function getFullName()
	{
		return $this->firstName.' '.$this->lastName;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getCountry()
	{
		return $this->country;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setCountry(CountryInterface $country = null)
	{
		if (null === $country) {
			$this->province = null;
		}
	
		$this->country = $country;
	
		return $this;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getProvince()
	{
		return $this->province;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setProvince(ProvinceInterface $province = null)
	{
		if (null === $this->country) {
			throw new \BadMethodCallException('Cannot define province on address without assigned country');
		}
	
		if (null !== $province && !$this->country->hasProvince($province)) {
			throw new \InvalidArgumentException(sprintf(
					'Cannot set province "%s", because it does not belong to country "%s"',
					$province->getName(),
					$this->country->getName()
			));
		}
	
		$this->province = $province;
	
		return $this;
	}
	
	public function isValid()
	{
		if (null === $this->country) {
			return false;
		}
	
		if (!$this->country->hasProvinces()) {
			return true;
		}
	
		if (null === $this->province) {
			return false;
		}
	
		if ($this->country->hasProvince($this->province)) {
			return true;
		}
	
		return false;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getStreet()
	{
		return $this->street;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setStreet($street)
	{
		$this->street = $street;
	
		return $this;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getCity()
	{
		return $this->city;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setCity($city)
	{
		$this->city = $city;
	
		return $this;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getPostcode()
	{
		return $this->postcode;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function setPostcode($postcode)
	{
		$this->postcode = $postcode;
	
		return $this;
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
	
	/**
	 * {@inheritdoc}
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
}
