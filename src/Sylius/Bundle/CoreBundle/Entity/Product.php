<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Sylius\Bundle\AssortmentBundle\Entity\CustomizableProduct as BaseProduct;
use Sylius\Bundle\ShippingBundle\Model\ShippingCategoryInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxCategoryInterface;
use Sylius\Bundle\TaxationBundle\Model\TaxableInterface;

/**
 * Sylius core product entity.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class Product extends BaseProduct implements TaxableInterface
{
    /*
     * Variant selection methods.
     *
     * 1) Choice - A list of all variants is displayed to user.
     *
     * 2) Match  - Each product option is displayed as select field.
     *             User selects the values and we match them to variant.
     */
    const VARIANT_SELECTION_CHOICE = 'choice';
    const VARIANT_SELECTION_MATCH  = 'match';

    /**
     * Short product description.
     * For lists displaying.
     *
     * @var string
     */
    protected $shortDescription;

    /**
     * if is a featured product
     *
     * @var boolean
     */
    protected $isFeatured;
    
    
    /**
     * Price without discount (offer).
     *
     * @var float
     */
    protected $priceWithoutDiscount;
    
    /**
     * Variant selection method.
     *
     * @var string
     */
    protected $variantSelectionMethod;

    /**
     * Taxons.
     *
     * @var Collection
     */
    protected $taxons;

    /**
     * Tax category.
     *
     * @var TaxCategoryInterface
     */
    protected $taxCategory;

    /**
     * Shipping category.
     *
     * @var ShippingCategoryInterface
     */
    protected $shippingCategory;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->setMasterVariant(new Variant());
        $this->taxons = new ArrayCollection();

        $this->variantSelectionMethod = self::VARIANT_SELECTION_CHOICE;
        
        $this->isFeatured = false;
        $this->isOnlyMayorista = false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSku()
    {
        return $this->getMasterVariant()->getSku();
    }

    /**
     * {@inheritdoc}
     */
    public function setSku($sku)
    {
        $this->getMasterVariant()->setSku($sku);

        return $this;
    }
       
    /**
     * Get the variant selection method.
     *
     * @return string
     */
    public function getVariantSelectionMethod()
    {
        return $this->variantSelectionMethod;
    }

    /**
     * Set variant selection method.
     *
     * @param string $variantSelectionMethod
     */
    public function setVariantSelectionMethod($variantSelectionMethod)
    {
        if (!in_array($variantSelectionMethod, array(self::VARIANT_SELECTION_CHOICE, self::VARIANT_SELECTION_MATCH))) {
            throw new \InvalidArgumentException(sprintf('Wrong variant selection method "%s" given.', $variantSelectionMethod));
        }

        $this->variantSelectionMethod = $variantSelectionMethod;

        return $this;
    }

    /**
     * Check if variant is selectable by simple variant choice.
     *
     * @return Boolean
     */
    public function isVariantSelectionMethodChoice()
    {
        return self::VARIANT_SELECTION_CHOICE === $this->variantSelectionMethod;
    }

    /**
     * Get pretty label for variant selection method.
     *
     * @return string
     */
    public function getVariantSelectionMethodLabel()
    {
        $labels = self::getVariantSelectionMethodLabels();

        return $labels[$this->variantSelectionMethod];
    }

    /**
     * Get taxons.
     *
     * @return Collection
     */
    public function getTaxons()
    {
        return $this->taxons;
    }

    /**
     * Set categorization taxons.
     *
     * @param Collection $taxons
     */
    public function setTaxons(Collection $taxons)
    {
        $this->taxons = $taxons;
    }

    /**
     * Gets product price.
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->getMasterVariant()->getPrice();
    }

    /**
     * Sets product price.
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->getMasterVariant()->setPrice($price);

        return $this;
    }

    /**
     * Set if is a featured product
     * 
     * @param boolean $isFeatured
     * @return Product
     */
    public function setIsFeatured($isFeatured)
    {
    	$this->isFeatured = $isFeatured;
    	
    	return $this;
    }
    
    /**
     * Get if is a featured product
     * 
     * @return boolean
     */
    public function getIsFeatured()
    {
    	return $this->isFeatured();
    }
       
    /**
     * Get if is a featured product
     *
     * @return boolean
     */
    public function isFeatured()
    {
    	return $this->isFeatured;
    }
    
    /**
     * Get product price without discount (offer).
     *
     * @return float
     */
    public function getPriceWithoutDiscount()
    {
    	return $this->priceWithoutDiscount;
    }
    
    /**
     * Set product price without discount (offer).
     *
     * @param float $price
     *
     * @return Product
     */
    public function setPriceWithoutDiscount($price)
    {
    	$this->priceWithoutDiscount = $price;
    
    	return $this;
    }
    
    public function isOffer()
    {
    	return $this->getPriceWithoutDiscount();
    }
    
    /**
     * Get product short description.
     *
     * @return string
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Set product short description.
     *
     * @param string $shortDescription
     *
     * @return Product
     */
    public function setShortDescription($shortDescription)
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTaxCategory()
    {
        return $this->taxCategory;
    }

    /**
     * {@inheritdoc}
     */
    public function setTaxCategory(TaxCategoryInterface $category = null)
    {
        $this->taxCategory = $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getShippingCategory()
    {
        return $this->shippingCategory;
    }

    /**
     * {@inheritdoc}
     */
    public function setShippingCategory(ShippingCategoryInterface $category = null)
    {
        $this->shippingCategory = $category;
    }

    /**
     * {@inheritdoc}
     */
    public function getImages()
    {
        return $this->getMasterVariant()->getImageS();
    }

    /**
     * {@inheritdoc}
     */
    public function getImage()
    {
        return $this->getMasterVariant()->getImages()->first();
    }

    /**
     * Get hash of variant selection methods and labels.
     *
     * @return array
     */
    public static function getVariantSelectionMethodLabels()
    {
        return array(
            self::VARIANT_SELECTION_CHOICE => 'Variant choice',
            self::VARIANT_SELECTION_MATCH  => 'Options matching',
        );
    }
}
