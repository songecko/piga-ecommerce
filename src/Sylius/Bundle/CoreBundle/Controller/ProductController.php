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
	
		$resource = $this->createNew();
		
		$optionRepository = $this->get('sylius.repository.option');
		$option = $optionRepository->findOneBy(array('name' => 'Talle'));
		$resource->addOption($option);
		
		$form = $this->getForm($resource);
	
		if ($request->isMethod('POST') && $form->bind($request)->isValid()) {
			$this->create($resource);
			$this->setFlash('success', 'create');
	
			return $this->redirectTo($resource);
		}
	
		if ($config->isApiRequest()) {
			return $this->handleView($this->view($form));
		}
	
		$view = $this
		->view()
		->setTemplate($config->getTemplate('create.html'))
		->setData(array(
				$config->getResourceName() => $resource,
				'form'                     => $form->createView()
		))
		;
	
		return $this->handleView($view);
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
        $config = $this->getConfiguration();

        $taxon = $this->get('sylius.repository.taxon')
            ->findOneByPermalink($permalink);

        if (!isset($taxon)) {
            throw new NotFoundHttpException('Requested taxon does not exist');
        }

        $paginator = $this
            ->getRepository()
            ->createByTaxonPaginator($taxon)
        ;

        $paginator->setCurrentPage($request->query->get('page', 1));
        $paginator->setMaxPerPage($config->getPaginationMaxPerPage());

        return $this->renderResponse('indexByTaxon.html', array(
            'taxon'    => $taxon,
            'products' => $paginator,
        ));
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
