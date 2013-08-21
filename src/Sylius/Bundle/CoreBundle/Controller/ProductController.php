<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Product controller.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductController extends ResourceController
{
	/**
	 * Create new resource or just display the form.
	 */
	public function createAction(Request $request)
	{
		$config = $this->getConfiguration();
	
		$product = $this->createNew();
		
		$optionRepository = $this->get('sylius.repository.option');
		$option = $optionRepository->findOneBy(array('name' => 'Talle'));
		$product->addOption($option);
		
		$form = $this->getForm($product);
	
		if ($request->isMethod('POST') && $form->bind($request)->isValid()) {
			$this->create($product);
			$this->setFlash('success', 'create');
	
			return $this->redirectTo($product);
		}
	
		if ($config->isApiRequest()) {
			return $this->handleView($this->view($form));
		}
	
		$view = $this
		->view()
		->setTemplate($config->getTemplate('create.html'))
		->setData(array(
				$config->getResourceName() => $product,
				'form'                     => $form->createView()
		))
		;
	
		return $this->handleView($view);
	}
	
	public function quickviewAction()
	{
		$config = $this->getConfiguration();
	
		$view =  $this
		->view()
		->setTemplate($config->getTemplate('quickview.html'))
		->setTemplateVar($config->getResourceName())
		->setData($this->findOr404())
		;
	
		return $this->handleView($view);
	}
	
	/**
	 * List products.
	 *
	 * @param Request $request
	 * @return \Symfony\Component\HttpFoundation\Response
	 * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
	 */
	public function indexAction(Request $request)
	{
		$filterDefaults = array(
			'is_only_mayorista' => false
		);
		
		return $this->renderResponse('index.html', $this->getViewParams($request, null, $filterDefaults));;
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
        $filterDefaults = array(
    		'is_only_mayorista' => false
    	);
    	
        return $this->renderResponse('indexByTaxon.html', $this->getViewParams($request, $permalink, $filterDefaults));
    }

    protected function getViewParams($request, $permalink = null, $filterDefaults = array())
    {
    	$config = $this->getConfiguration();
    	
    	$params = array(); //Empty array by default
    	
    	$filter = $request->query->get('f');
    	if (!$filter)
    		$filter = array();    	
    	$filter = array_merge($filter, $filterDefaults);
    	
    	if($permalink)
    	{
    		$taxon = $this->get('sylius.repository.taxon')
    			->findOneByPermalink($permalink);
    	
    		if (!isset($taxon)) {
	    		throw new NotFoundHttpException('Requested taxon does not exist');
    		}
    	 
	    	$paginator = $this
    			->getRepository()
    			->createByTaxonPaginator($taxon, $filter)
    		;
	    	
	    	$params['taxon'] = $taxon;
    	}else 
    	{
    		$paginator = $this
    			->getRepository()
    			->createFilterPaginator($filter)
    		;
    	}
    	
    	$paginator->setMaxPerPage($config->getPaginationMaxPerPage());
    	$paginator->setCurrentPage($request->query->get('page', 1));
    	
    	$params['products'] = $paginator;
    	
    	return $params;
    }
    
    public function renderResponse($templateName, array $parameters = array())
    {
    	$request = $this->getRequest();
    	$config = $this->getConfiguration();
    	
    	if($request->isXmlHttpRequest())
    	{
    		$arguments = $config->getArguments();
    		$templateName = $arguments['spPageTemplate'];
    	}else 
    	{
    		$templateName = $this->getConfiguration()->getTemplate($templateName);
    	}
    	
    	return $this->render($templateName, $parameters);
    }
    
    /**
     * Render product filter form.
     *
     * @param Request
     */
    public function filterFormAction(Request $request)
    {
        $form = $this->getFormFactory()->createNamed('criteria', 'sylius_product_filter');

        return $this->renderResponse('filterForm.html', array(
            'form' => $form->createView()
        ));
    }

    private function getFormFactory()
    {
        return $this->get('form.factory');
    }
}
