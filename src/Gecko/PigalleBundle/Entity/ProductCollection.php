<?php

namespace Gecko\PigalleBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Gecko\PigalleBundle\Entity\ProductCollectionInterface;
use Sylius\Bundle\CoreBundle\Model\ImageInterface;
use Sylius\Bundle\AssortmentBundle\Model\Option\OptionInterface;
use Sylius\Bundle\CoreBundle\Model\VariantInterface;

/**
 * Product collection
 */
class ProductCollection implements ProductCollectionInterface
{
	protected $id;
	protected $name;
	protected $slug;
	protected $description;
    protected $shortDescription;
    protected $images;
    protected $taxons;
    protected $options;
    protected $createdAt;
    protected $updatedAt;
    
    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->taxons = new ArrayCollection();
        $this->options = new ArrayCollection();
        $this->createdAt = new \DateTime();
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
    
    public function getSlug()
    {
    	return $this->slug;
    }
    
    public function setSlug($slug)
    {
    	$this->slug = $slug;
    }
    
    public function getDescription()
    {
    	return $this->description;
    }
    
    public function setDescription($description)
    {
    	$this->description = $description;
    }
    
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function hasImage(ImageInterface $image)
    {
    	return $this->images->contains($image);
    }
    
    public function getImages()
    {
    	return $this->images;
    }
    
    public function getImage()
    {
    	return $this->getImages()->first();
    }
    
    public function addImage(ImageInterface $image)
    {
    	if (!$this->hasImage($image)) 
    	{
    		$image->setProductCollection($this);
    		$this->images->add($image);
    	}
    }
    
    public function removeImage(ImageInterface $image)
    {
    	$image->setProductCollection(null);
    	$this->images->removeElement($image);
    }
    
    public function getTaxons()
    {
    	return $this->taxons;
    }
    
    public function setTaxons(Collection $taxons)
    {
    	$this->taxons = $taxons;
    }
    
    public function hasOptions()
    {
    	return !$this->options->isEmpty();
    }
    
    public function getOptions()
    {
    	return $this->options;
    }
    
    public function setOptions(Collection $options)
    {
    	$this->options = $options;
    }
    
    public function addOption(OptionInterface $option)
    {
    	if (!$this->hasOption($option)) {
    		$this->options->add($option);
    	}
    }
    
    public function removeOption(OptionInterface $option)
    {
    	if ($this->hasOption($option)) {
    		$this->options->removeElement($option);
    	}
    }
    
    public function hasOption(OptionInterface $option)
    {
    	return $this->options->contains($option);
    }
    
    public function getCreatedAt()
    {
    	return $this->createdAt;
    }
    
    public function setCreatedAt(\DateTime $createdAt)
    {
    	$this->createdAt = $createdAt;
    }
    
    public function getUpdatedAt()
    {
    	return $this->updatedAt;
    }
    
    public function setUpdatedAt(\DateTime $updatedAt)
    {
    	$this->updatedAt = $updatedAt;
    }
}
