<?php

namespace Gecko\PigalleBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sylius\Bundle\CoreBundle\Controller\ProductController;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Mayorista controller.
 */
class MayoristaController extends ProductController
{	
	/**
	 * List products.
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function indexAction(Request $request)
	{
		$taxons = new ArrayCollection();
		
		$taxonomyRepository = $this->get('sylius.repository.taxonomy');
		$taxonomies = $taxonomyRepository->findAll();
		foreach ($taxonomies as $taxonomy)
		{
			foreach($taxonomy->getTaxonsForMayoristas() as $taxon)
			{
				$taxons->add($taxon);
			}
		}
		
		return $this->renderResponse('index.html', $this->getViewParams($request, null, array('taxons' => $taxons)));
	}
	
    /**
     * List products categorized under given taxon.
     *
     * @param Request $request
     * @param $permalink
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function indexByTaxonAction(Request $request, $permalink)
    {
        return $this->renderResponse('indexByTaxon.html', $this->getViewParams($request, $permalink));
    }
       
    public function orderAction(Request $request)
    {
    	$product = $this->findOr404();
    	$user = $this->get('security.context')->getToken()->getUser();
    	
    	try {
    		$message = \Swift_Message::newInstance()
    		->setContentType("text/html")
    		->setSubject('Pigalle - Pedido mayorista')
    		->setFrom($user->getEmail())
    		->setTo('info@pigalle.com.ar')
    		->setBody(
    				$this->renderView(
    						'PigalleBundle:Mayorista:order_email.html.twig',
    						array(
    								'product'   => $product,
    								'user'   => $user
    						)
    				)
    		);
    	
    		$this->get('mailer')->send($message);
    	}catch (Swift_SwiftException $e)
    	{
    	}
    	
    	return $this->render("PigalleBundle:Mayorista:ordered.html.twig");
    }
    
    public function registerSuccessAction(Request $request)
    {
    	return $this->render("PigalleBundle:Mayorista:registerSuccess.html.twig");
    }
}