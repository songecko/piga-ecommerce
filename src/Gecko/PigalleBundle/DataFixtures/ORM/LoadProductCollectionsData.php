<?php

namespace Gecko\PigalleBundle\DataFixtures\ORM;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;
use Sylius\Bundle\CoreBundle\Entity\Taxon;
use Gecko\PigalleBundle\Entity\ProductCollection;
use Sylius\Bundle\CoreBundle\DataFixtures\ORM\DataFixture;

class LoadProductCollectionsData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {	
        // Products...
        for ($i = 1; $i <= 60; $i++) {	        
            switch (rand(0, 3)) {
                case 0:
                    $manager->persist($this->createProduct('Zapato', $i, $manager));
                break;

                case 1:
                    $manager->persist($this->createProduct('Bota', $i, $manager));
                break;

                case 2:
                    $manager->persist($this->createProduct('Sandalia', $i, $manager));
                break;

                case 3:
                    $manager->persist($this->createProduct('Taco', $i, $manager));
                break;
            }

            if (0 === $i % 20) {
                $manager->flush();
            }
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 16;
    }

    /**
     * Create new product instance.
     *
     * @param string $shoeType
     * @param integer $i
     * @return ProductCollectionInterface
     */
    private function createProduct($shoeType, $i, $manager)
    {    	
    	$product = new ProductCollection();
    	 
    	$product->setName(sprintf('%s "%s"', $shoeType,  $this->faker->word));
    	$product->setDescription($this->faker->paragraph);
    	$product->setShortDescription($this->faker->sentence);
    	
    	$taxonSeason = $this->faker->randomElement(array(Taxon::TAXON_SEASON_SUMMER, Taxon::TAXON_SEASON_WINTER));
    	$this->setTaxons($product, array($shoeType.'s', $taxonSeason));
    	    	
    	$product->addOption($this->getReference('Sylius.Option.Talle'));
    	
    	$productName = explode(' ', $product->getName());
    	$image = clone $this->getReference(
    			'Pigalle.ProductCollectionImage.'.strtolower($productName[0])
    	);
    	$manager->persist($image);
    	$product->addImage($image);
    	
    	$this->setReference('Pigalle.ProductCollection-'.$i, $product);    	
    	 
    	return $product;
    }

    /**
     * Add product to given taxons.
     *
     * @param ProductCollection $product
     * @param array                        $taxonNames
     */
    private function setTaxons(ProductCollection $product, array $taxonNames)
    {
        $taxons = new ArrayCollection();

        foreach ($taxonNames as $taxonName) {
            $taxons->add($this->getReference('Sylius.Taxon.'.$taxonName));
        }

        $product->setTaxons($taxons);
    }
}