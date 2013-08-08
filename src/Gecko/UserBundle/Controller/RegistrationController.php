<?php

namespace Gecko\UserBundle\Controller;

use FOS\UserBundle\Controller\RegistrationController as BaseController;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\RedirectResponse;

class RegistrationController extends BaseController
{
    public function registerAction()
    {
        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');

        //Register action
        $form = $this->container->get('fos_user.registration.form');
        $formHandler = $this->container->get('fos_user.registration.form.handler');
        $confirmationEnabled = $this->container->getParameter('fos_user.registration.confirmation.enabled');
        
        $process = $formHandler->process($confirmationEnabled);
        if ($process) {
        	$user = $form->getData();
        
        	$authUser = false;
        	if ($confirmationEnabled) {
        		$this->container->get('session')->set('fos_user_send_confirmation_email/email', $user->getEmail());
        		$route = 'fos_user_registration_check_email';
        	} else {
        		$authUser = true;
        		$route = 'fos_user_registration_confirmed';
        	}
        
        	$this->setFlash('fos_user_success', 'registration.flash.user_created');
        	$url = $this->container->get('router')->generate($route);
        	$response = new RedirectResponse($url);
        
        	if ($authUser) {
        		$this->authenticateUser($user, $response);
        	}
        
        	return $response;
        }
        
        return $this->container->get('templating')->renderResponse('FOSUserBundle:Registration:register.html.'.$this->getEngine(), array(
        	'last_username' => $lastUsername,
        	'error'         => $error,
        	'csrf_token' => $csrfToken,
        	'form' => $form->createView(),
        ));
    }
}