<?php

namespace Gecko\UserBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * User controller.
 */
class UserController extends ResourceController
{
	public function toggleMayoristaAction(Request $request)
	{
		$user = $this->findOr404();
		 
		if($user->hasRole('ROLE_USER_MAYORISTA'))
		{
			$user->removeRole('ROLE_USER_MAYORISTA');
		}else 
		{
			$user->addRole('ROLE_USER_MAYORISTA');
		}
		
		$this->getManager()->flush();
		
		return $this->redirectToIndex();
	}
}