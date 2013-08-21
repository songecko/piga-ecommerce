<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Repository;

use Sylius\Bundle\AssortmentBundle\Entity\CustomizableProductRepository;
use Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Product repository.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductRepository extends CustomizableProductRepository
{	
	/**
	 * Find for featured products
	 * 
	 * @param number $max
	 * @return array
	 */
	public function findFeaturedProducts($max = null)
	{
		$criteria = array(
			'only_with_stock' => true,
			'is_featured' => true
		);
		$qb =  $this->createFilterQueryBuilder($criteria);
		
		if($max)
		{
			$qb->setMaxResults($max);
		}
			
		return $qb->getQuery()->getResult();
	}
	
	public function getByTaxonQueryBuilder(TaxonInterface $taxon, $criteria = array(), $sorting = array())
	{			
		$queryBuilder = $this->createFilterQueryBuilder($criteria, $sorting);
		
		$queryBuilder
			->innerJoin('product.taxons', 'taxon')
			->andWhere('taxon = :taxon')
			->setParameter('taxon', $taxon)
		;
		
		return $queryBuilder;
	}
	
    /**
     * Create paginator for products categorized
     * under given taxon.
     *
     * @param TaxonInterface
     *
     * @return PagerfantaInterface
     */
    public function createByTaxonPaginator(TaxonInterface $taxon, $criteria = array(), $sorting = array())
    {
    	$criteria['only_with_stock'] = true;
        $queryBuilder = $this->getByTaxonQueryBuilder($taxon, $criteria, $sorting);

        return $this->getPaginator($queryBuilder);
    }

    /**
     * Create filter paginator.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return PagerfantaInterface
     */
    public function createFilterPaginator($criteria = array(), $sorting = array())
    {
    	$criteria['only_with_stock'] = true;    	
        return $this->getPaginator($this->createFilterQueryBuilder($criteria, $sorting));
    }

    /**
     * Create filter query builder.
     *
     * @param array $criteria
     * @param array $sorting
     *
     * @return QueryBuilder
     */
    public function createFilterQueryBuilder($criteria = array(), $sorting = array())
    {
    	$queryBuilder = $this->getQueryBuilder()
			->andWhere('variant.master = 1')
			->groupBy('product.id')	;
    
    	if (!empty($criteria['name'])) 
    	{
    		$queryBuilder
    		->andWhere('product.name LIKE :name')
    		->setParameter('name', '%'.$criteria['name'].'%')
    		;
    	}
    	if (!empty($criteria['sku'])) 
    	{
    		$queryBuilder
    		->andWhere('variant.sku = :sku')
    		->setParameter('sku', $criteria['sku'])
    		;
    	}
    	if (isset($criteria['only_offer']) && $criteria['only_offer'] == true) 
    	{
    		$queryBuilder
    			->andWhere('product.priceWithoutDiscount > 0')
    		;
    	}
    	
    	if (isset($criteria['only_with_stock']) && $criteria['only_with_stock'] == true) 
    	{
    		$queryBuilder->andWhere('variant.onHand > 0');
    	}
    	
    	if (isset($criteria['is_featured']))
    	{
    		$queryBuilder
    			->andWhere('product.isFeatured = :isFeatured')
    			->setParameter('isFeatured', $criteria['is_featured']);
    	}
    
    	if (isset($criteria['is_only_mayorista']) && $criteria['is_only_mayorista'] === false)
    	{
    		$queryBuilder
    			->andWhere('product.isOnlyMayorista = false');
    	}
    	
    	if (empty($sorting)) {
    		if (!is_array($sorting)) {
    			$sorting = array();
    		}
    		$sorting['updatedAt'] = 'desc';
    	}
    
    	$this->applySorting($queryBuilder, $sorting);
    
    	return $queryBuilder;
    }
    
    /**
     * Find X recently added products.
     *
     * @param integer $limit
     *
     * @return ProductInterface[]
     */
    public function findLatest($limit = 10)
    {
        return $this->findBy(array(), array('createdAt' => 'desc'), $limit);
    }
}
