<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\AssortmentBundle\Model\CustomizableProductInterface;
use Sylius\Bundle\CoreBundle\Entity\Product;
use Sylius\Bundle\CoreBundle\Entity\Taxon;

/**
 * Default assortment products to play with Sylius sandbox.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class LoadProductsData extends DataFixture
{
    /**
     * Total variants created.
     *
     * @var integer
     */
    private $totalVariants;

    /**
     * SKU collection.
     *
     * @var array
     */
    private $skus;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->skus = array();
    }

    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->productPropertyClass = $this->container->getParameter('sylius.model.product_property.class');
	
        // Products...
        for ($i = 1; $i <= 120; $i++) {	        
            switch (rand(0, 3)) {
                case 0:
                    $manager->persist($this->createProduct('Zapato', $i));
                break;

                case 1:
                    $manager->persist($this->createProduct('Bota', $i));
                break;

                case 2:
                    $manager->persist($this->createProduct('Sandalia', $i));
                break;

                case 3:
                    $manager->persist($this->createProduct('Taco', $i));
                break;
            }

            if (0 === $i % 20) {
                $manager->flush();
            }
        }

        $manager->flush();

        // Define constant with number of total variants created.
        define('SYLIUS_FIXTURES_TOTAL_VARIANTS', $this->totalVariants);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 6;
    }

    /**
     * Create new product instance.
     *
     * @param string $shoeType
     * @param integer $i
     * @return ProductInterface
     */
    private function createProduct($shoeType, $i)
    {    	
    	$product = $this->getProductRepository()->createNew();
    	 
    	//$product->setTaxCategory($this->getTaxCategory('Taxable goods'));
    	$product->setName(sprintf('%s "%s"', $shoeType,  $this->faker->word));
    	$product->setDescription($this->faker->paragraph);
    	$product->setShortDescription($this->faker->sentence);
    	$product->setVariantSelectionMethod(Product::VARIANT_SELECTION_CHOICE);
    	 
    	$this->addMasterVariant($product);
    	
    	$taxonSeason = $this->faker->randomElement(array(Taxon::TAXON_SEASON_SUMMER, Taxon::TAXON_SEASON_WINTER));
    	$this->setTaxons($product, array($shoeType.'s', $taxonSeason));
    	
    	if($i % 5 == 0)
    	{
    		$this->setPriceWithoutDiscount($product);	
    	}
    	
    	if($i % 12 == 0)
    	{
    		$product->setIsFeatured(true);
    	}
    	
    	$product->addOption($this->getReference('Sylius.Option.Talle'));
    	
    	$this->generateVariants($product);
    	
    	$this->setReference('Sylius.Product-'.$i, $product);    	
    	 
    	return $product;
    }
    
    /**
     * Generates all possible variants with random prices.
     *
     * @param CustomizableProductInterface $product
     */
    private function generateVariants(CustomizableProductInterface $product)
    {
        $this
            ->getVariantGenerator()
            ->generate($product)
        ;

        foreach ($product->getVariants() as $variant) {
           /* $variant->setAvailableOn($this->faker->dateTime);
            $variant->setPrice($product->getMasterVariant()->getPrice());
            $variant->setSku($this->getUniqueSku());*/
            $variant->setOnHand($this->faker->randomNumber(1));
            $variant->setAvailableOnDemand(false);
            
            $this->setReference('Sylius.Variant-'.$this->totalVariants, $variant);
            $this->totalVariants++;
        }
    }

    /**
     * Adds master variant to product.
     *
     * @param CustomizableProductInterface $product
     * @param string                       $sku
     */
    private function addMasterVariant(CustomizableProductInterface $product, $sku = null)
    {
        if (null === $sku) {
            $sku = $this->getUniqueSku();
        }

        $variant = $product->getMasterVariant();
        $variant->setProduct($product);
        $variant->setPrice($this->faker->randomNumber(4));
        $variant->setSku($sku);
        $variant->setAvailableOn($this->faker->dateTime);
        $variant->setAvailableOnDemand(false);
        $variant->setOnHand($this->faker->randomNumber(1));

        $productName = explode(' ', $product->getName());
        $image = clone $this->getReference(
            'Sylius.Image.'.strtolower($productName[0])
        );
        $variant->addImage($image);

        $this->setReference('Sylius.Variant-'.$this->totalVariants, $variant);
        $this->totalVariants++;

        $product->setMasterVariant($variant);
    }

    /**
     * Adds property to product with given value.
     *
     * @param CustomizableProductInterface $product
     * @param string                       $propertyReference
     * @param string                       $value
     */
    private function addProperty(CustomizableProductInterface $product, $name, $value)
    {
        $property = $this->getProductPropertyRepository()->createNew();
        $property->setProperty($this->getReference('Sylius.Property.'.$name));
        $property->setProduct($product);
        $property->setValue($value);

        $product->addProperty($property);
    }

    /**
     * Add product to given taxons.
     *
     * @param CustomizableProductInterface $product
     * @param array                        $taxonNames
     */
    private function setTaxons(CustomizableProductInterface $product, array $taxonNames)
    {
        $taxons = new ArrayCollection();

        foreach ($taxonNames as $taxonName) {
            $taxons->add($this->getReference('Sylius.Taxon.'.$taxonName));
        }

        $product->setTaxons($taxons);
    }

    /**
     * Get tax category by name.
     *
     * @param string $name
     *
     * @return TaxCategoryInterface
     */
    private function getTaxCategory($name)
    {
        return $this->getReference('Sylius.TaxCategory.'.ucfirst($name));
    }

    private function setPriceWithoutDiscount($product)
    {
    	$variant = $product->getMasterVariant();
    	
    	if($variant)
    	{
	    	do {
	    		$price = $this->faker->randomNumber(4);
	    	} while ($price <= $variant->getPrice());
	    	
	    	$product->setPriceWithoutDiscount($price);
    	}
    }
    
    /**
     * Get unique SKU.
     *
     * @param integer $length
     *
     * @return string
     */
    private function getUniqueSku($length = 5)
    {
        do {
            $sku = $this->faker->randomNumber($length);
        } while (in_array($sku, $this->skus));

        $this->skus[] = $sku;

        return $sku;
    }

    /**
     * Get unique ISBN number.
     *
     * @return string
     */
    private function getUniqueISBN()
    {
        return $this->getUniqueSku(13);
    }   
}
