<?php

namespace Sylius\Bundle\CoreBundle\Entity;

use Sylius\Bundle\TaxonomiesBundle\Entity\Taxonomy as BaseTaxonomy;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Sylius core taxononomy entity.
 *
 */
class Taxonomy extends BaseTaxonomy
{

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->setRoot(new Taxon());
    }

    public function getTaxonsForColecciones()
    {
    	$taxons = $this->getTaxons();
    	$taxonsForColecciones = new ArrayCollection();
    	foreach ($taxons as $taxon)
    	{
    		if($taxon->isVisibleColecciones())
    		{
    			$taxonsForColecciones->add($taxon);
    		}
    	}
    
    	return $taxonsForColecciones;
    }
    
    public function getTaxonsForMayoristas()
    {
    	$taxons = $this->getTaxons();
    	$taxonsForMayoristas = new ArrayCollection();
    	foreach ($taxons as $taxon)
    	{
    		if($taxon->isVisibleMayoristas())
    		{
    			$taxonsForMayoristas->add($taxon);
    		}
    	}
    
    	return $taxonsForMayoristas;
    }
}
