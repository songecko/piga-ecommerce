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
    
    public function collectionAction(Request $request)
    {
    	$filters = $request->query->get('f');
    	
    	$taxonomyRepository = $this->get('sylius.repository.taxonomy');
    	$taxonomies = $taxonomyRepository->findAll();
    	
    	$taxon = $this->get('sylius.repository.taxon')
            ->findOneByPermalink('zapatos');

        if (!isset($taxon)) {
            $taxon = null;
        }
    	
    	return $this->render('PigalleBundle:Main:collection.html.twig', array(
    		'taxonomies' => $taxonomies,
    		'filters' => $filters,
    		'taxon' => $taxon
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
