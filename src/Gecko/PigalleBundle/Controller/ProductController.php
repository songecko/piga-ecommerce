<?php

namespace Gecko\PigalleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends Controller
{
    public function featuredProductsAction($max = 4)
    {
    	$productRepository = $this->get('sylius.repository.product');
    	$products = $productRepository->findFeaturedProducts($max);
    	
        return $this->render('PigalleBundle:Product:featuredProducts.html.twig', array(
        	'products' => $products		
        ));
    }
    
    public function filtersAction(Request $request, $route = 'pigalle_product_index', $routeByTaxon = 'pigalle_product_index_by_taxon')
	{
		$filters = $request->query->get('f');
		
    	$taxonomyRepository = $this->get('sylius.repository.taxonomy');    	 
    	$taxonomies = $taxonomyRepository->findAll();
    	
    	return $this->render('PigalleBundle:Product:filters.html.twig', array(
    			'filters' => $filters,
    			'taxonomies' => $taxonomies, 
    			'route' => $route, 
    			'routeByTaxon' => $routeByTaxon
    	));
    }
    
    public function filtersMayoristaAction(Request $request, $route = 'pigalle_product_index', $routeByTaxon = 'pigalle_product_index_by_taxon')
    {
    	$filters = $request->query->get('f');
    
    	$taxonomyRepository = $this->get('sylius.repository.taxonomy');
    	$taxonomies = $taxonomyRepository->findAll();
    
    	return $this->render('PigalleBundle:Mayorista:filters.html.twig', array(
    			'filters' => $filters,
    			'taxonomies' => $taxonomies,
    			'route' => $route,
    			'routeByTaxon' => $routeByTaxon
    	));
    }
}
