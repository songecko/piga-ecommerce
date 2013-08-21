<?php

namespace Gecko\PigalleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    public function indexAction()
    {
        return $this->render('PigalleBundle:Main:index.html.twig');
    }
    
    public function faqAction()
    {
    	return $this->render('PigalleBundle:Main:faq.html.twig');
    }
    
    public function collectionAction(Request $request)
    {
    	//Get all taxonomies    	
    	$taxonomyRepository = $this->get('sylius.repository.taxonomy');
    	$taxonomies = $taxonomyRepository->findAll();
    	
    	//Get the season taxon
    	$seasonQuery = $request->query->get('s');
    	$season = null;
    	if($seasonQuery)
    	{
    		$season = $this->get('sylius.repository.taxon')
            	->findOneByPermalink($seasonQuery);
    	}    	
    	if(!$season)
    	{
    		foreach ($taxonomies as $taxonomy)
    		{
    			if($taxonomy->getName() == 'Temporada')
    				$season = $taxonomy->getTaxons()->first();
    		}
    	}
    	
    	//Get the shoe type taxon
    	$typeQuery = $request->query->get('t');
    	$type = null;
    	if($typeQuery)
    	{
    		$type = $this->get('sylius.repository.taxon')
    			->findOneByPermalink($typeQuery);
    	}
    	
    	$queryBuilder = $this->get('sylius.repository.product')->getByTaxonQueryBuilder($season, array('only_with_stock' => false));
    	
    	if($type)
    	{
    		$queryBuilder->innerJoin('product.taxons', 'taxon2')
				->andWhere('taxon2 = :taxon2')
				->setParameter('taxon2', $type);
    	}
  
    	$products =	$queryBuilder->getQuery()->getResult();
    	
    	return $this->render('PigalleBundle:Main:collection.html.twig', array(
    		'taxonomies' => $taxonomies,
    		'season' => $season,
    		'products' => $products
    	));
    }
    
    public function laMarcaAction()
    {
    	return $this->render('PigalleBundle:Main:laMarca.html.twig');
    }
    
    public function localesAction()
    {
    	return $this->render('PigalleBundle:Main:locales.html.twig');
    }
}
