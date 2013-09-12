<?php

namespace Gecko\NewsletterBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Gecko\NewsletterBundle\Entity\Subscriber;

/**
 * Newsletter controller.
 */
class SubscriberController extends ResourceController
{	
	public function subscribeAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		
		$subscriber = new Subscriber();
		
		$form = $this->createForm($this->container->get('gecko_newsletter.subscriber.form.type'), $subscriber);
	
		if ('POST' == $request->getMethod()) 
		{
			$form->bindRequest($request);
	
			if ($form->isValid()) 
			{
				$em->persist($subscriber);
				$em->flush();
				
				try {
					$message = \Swift_Message::newInstance()
					->setContentType("text/html")
					->setSubject('Pigalle - Adhesion al newsletter')
					->setFrom('info@pigalle.com.ar')
					->setTo($subscriber->getEmail())
					->setBody(
						$this->renderView(
							'GeckoNewsletterBundle:Frontend/Subscriber:subscribe_email.html.twig',
							array(
								'subscriber'   => $subscriber
							)
						)
					);
				  
					$this->get('mailer')->send($message);					
				}catch (Swift_SwiftException $e)
				{
				}
			}
		}
	
		return $this->render('GeckoNewsletterBundle:Frontend/Subscriber:subscribe.html.twig', array(
			'form'  => $form->createView()
		));
	}
	
	/**
	 * Unsubscribe.
	 */
	public function unsubscribeAction($token)
	{
		$em = $this->getDoctrine()->getManager();
		$subscriber = $this->findOr404(array('confirmationToken' => $token));
		
		$subscriber->setEnabled(false);
		$em->flush();
		
		return $this->render('GeckoNewsletterBundle:Frontend/Subscriber:unsubscribed.html.twig', array(
		));
	}
	
	/**
	 * Confirm subscriber action.
	 */
	public function confirmAction($token)
	{
		$em = $this->getDoctrine()->getManager();
		$subscriber = $this->findOr404(array('confirmationToken' => $token));	
	
		$subscriber->setEnabled(true);
		$em->flush();
		
		return $this->render('GeckoNewsletterBundle:Frontend/Subscriber:subscribed.html.twig', array(
		));
	}
}