<?php

namespace Gecko\PigalleBundle\Repository;

use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface;
use Doctrine\ORM\QueryBuilder;

/**
 * Product repository.
 */
class ProductCollectionRepository extends EntityRepository
{	
	protected function getQueryBuilder()
	{
		return parent::getQueryBuilder()
		->select('product_collection, image, option')
		->leftJoin('product_collection.images', 'image')
		->leftJoin('product_collection.options', 'option')
		;
	}
	
	public function getByTaxonQueryBuilder(TaxonInterface $taxon, $criteria = array(), $sorting = array())
	{			
		$queryBuilder = $this->createFilterQueryBuilder($criteria, $sorting);
		
		$queryBuilder
			->innerJoin('product_collection.taxons', 'taxon')
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
			->groupBy('product_collection.id')	;
    
    	if (!empty($criteria['name'])) 
    	{
    		$queryBuilder
    		->andWhere('product_collection.name LIKE :name')
    		->setParameter('name', '%'.$criteria['name'].'%')
    		;
    	}
    	
    	if (!empty($criteria['taxons']))
    	{
    		$taxonIds = array();
    		foreach ($criteria['taxons'] as $taxon)
    		{
    			$taxonIds[] = $taxon->getId();
    		}
    		
    		$queryBuilder
    		->innerJoin('product_collection.taxons', 'taxon')
    		->andWhere('taxon IN (:taxons)')
    		->setParameter('taxons', $taxonIds)
    		;
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
    
    protected function getAlias()
    {
    	return 'product_collection';
    }
}
