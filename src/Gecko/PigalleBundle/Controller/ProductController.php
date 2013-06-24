<?php

namespace Gecko\PigalleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
}
