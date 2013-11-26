<?php

namespace Gecko\PigalleBundle\Entity;

/**
 * Local entity.
 *
 */
class Local
{
	const LOCATION_BUENOS_AIRES = "buenos_aires";
	const LOCATION_PROVINCIAS = "provincias";
	const LOCATION_PAISES_LIMITROFES = "paises_limitrofes";
	
	public static $LOCATIONS = array(
		self::LOCATION_BUENOS_AIRES =>  "Gran Buenos Aires",
		self::LOCATION_PROVINCIAS => "Provincias",
		self::LOCATION_PAISES_LIMITROFES => "Paises limítrofes"
	);
	
	protected $id;
	protected $name;
	protected $address;
	protected $address2;
	protected $location;
	protected $phone;
	protected $email;
	protected $isFeatured;
	protected $createdAt;
	protected $updatedAt;
	
	
	public function __construct()
	{
		$this->createdAt = new \DateTime('now');
		$this->isFeatured = false;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function setAddress($address)
	{
		$this->address = $address;
	}
	
	public function getAddress2()
	{
		return $this->address2;
	}
	
	public function setAddress2($address2)
	{
		$this->address2 = $address2;
	}
	
	public function getLocationName()
	{
		return self::$LOCATIONS[$this->location];		
	}
	
	public function getLocation()
	{
		return $this->location;
	}
	
	public function setLocation($location)
	{
		$this->location = $location;
	}
	
	public function getPhone()
	{
		return $this->phone;
	}
	
	public function setPhone($phone)
	{
		$this->phone = $phone;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function setEmail($email)
	{
		$this->email = $email;
	}
	
	public function getIsFeatured()
	{
		return $this->isFeatured;
	}
	
	public function setIsFeatured($isFeatured)
	{
		$this->isFeatured = $isFeatured;
	}
	
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	public function setCreatedAt($createdAt)
	{
		$this->createdAt = $createdAt;
	}
	
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
	
	public function setUpdatedAt($updatedAt)
	{
		$this->updatedAt = $updatedAt;
	}
}
