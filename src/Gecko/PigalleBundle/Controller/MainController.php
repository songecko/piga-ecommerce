<?php

namespace Gecko\PigalleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Gecko\PigalleBundle\Form\Type\ContactType;

class MainController extends Controller
{
    public function indexAction()
    {
    	$repository = $this->getDoctrine()->getRepository('PigalleBundle:PigalleSlide');
    	$query = $repository->createQueryBuilder('ps')
	    	->where('ps.isActive = :isActive')
	    	->setParameter('isActive', true)
	    	->orderBy('ps.updatedAt', 'DESC')
    		->getQuery();    	
    	$pigalleSlides = $query->getResult();
    	
        return $this->render('PigalleBundle:Main:index.html.twig', array(
        	'pigalleSlides' => $pigalleSlides
        ));
    }
    
    public function faqAction()
    {
    	return $this->render('PigalleBundle:Main:faq.html.twig');
    }
    
    public function contactAction(Request $request)
    {
    	$form = $this->createForm(new ContactType());
    	
    	if ($request->isMethod('POST')) 
    	{
    		$form->bind($request);
    		
    		if ($form->isValid()) {
    			$message = \Swift_Message::newInstance()
    			->setSubject("[Pigalle] Nuevo contacto desde la web")
    			->setFrom($form->get('email')->getData())
    			->setTo('songecko@gmail.com')
    			->setBody(
    					$this->renderView(
    						'PigalleBundle:Main:contact_mail.html.twig',
    						array(
    							'values' => $form->getData(),
    						)
    					)
    			);
    	
    			$this->get('mailer')->send($message);
    	
    			$request->getSession()->getFlashBag()->add('sended', 'Su consulta fué enviada correctamente.<br>Le responderemos a la brevedad.');
    	
    			return $this->redirect($this->generateUrl('pigalle_contact'));
    		}
    	}
    	
    	return $this->render('PigalleBundle:Main:contact.html.twig', array(
    		'form' => $form->createView()
    	));
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
    				$season = $taxonomy->getTaxonsForColecciones()->last();
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
    	
    	$queryBuilder = $this->get('sylius.repository.product_collection')->getByTaxonQueryBuilder($season, array());
    	
    	if($type)
    	{
    		$queryBuilder->innerJoin('product_collection.taxons', 'taxon2')
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
    
    public function checkoutSuccessAction()
    {
    	return $this->render('PigalleBundle:Main:checkoutSuccess.html.twig');
    }
    
    public function laMarcaAction()
    {
    	return $this->render('PigalleBundle:Main:laMarca.html.twig');
    }
    
    public function localesAction()
    {
    	return $this->render('PigalleBundle:Main:locales.html.twig');
    }
    
    public function unauthorizedAction()
    {
    	return $this->render('PigalleBundle:Main:unauthorized.html.twig');
    }
    
    public function newsletterSubscribeWidgetAction()
    {
    	$form = $this->container->get('form.factory')->create($this->container->get('gecko_newsletter.subscriber.form.type'));
    
    	return $this->render('PigalleBundle:Main:newsletter_subscribe_widget.html.twig', array(
    			'form'  => $form->createView()
    	));
    }
}
