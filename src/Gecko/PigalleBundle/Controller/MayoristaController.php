<?php

namespace Gecko\PigalleBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Sylius\Bundle\CoreBundle\Controller\ProductController;

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
		return $this->renderResponse('index.html', $this->getViewParams($request));
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
    
    public function registerSuccessAction(Request $request)
    {
    	return $this->render("PigalleBundle:Mayorista:registerSuccess.html.twig");
    }
}