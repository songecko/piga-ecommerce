<?php

namespace Gecko\NewsletterBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Gecko\NewsletterBundle\Entity\Newsletter;

/**
 * Newsletter controller.
 */
class NewsletterController extends ResourceController
{
	public function sendAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		
		$newsletter = $this->findOr404();
		 
		$toList = array();
		
		//Try send the emails
		$numSent = 0;
		foreach ($newsletter->getSubscriberList()->getSubscribers() as $subscriber)
		{
			$to = array(
				'subscriber' => $subscriber, 
				'sent' => false
			);
			
			try {
				$message = \Swift_Message::newInstance()
				->setContentType("text/html")
				->setSubject('Pigalle: '.$newsletter->getTitle())
				->setFrom('info@pigalle.com.ar')
				->setTo($subscriber->getEmail())
				->setBody(
					$this->renderView(
						'GeckoNewsletterBundle:Backend/Newsletter/Template:'.Newsletter::$NEWSLETTER_TEMPLATE_FILES[$newsletter->getTemplateName()],
						array(
							'newsletter'  => $newsletter,
							'subscriber'   => $subscriber,
							'confirmationToken'   => $subscriber->generateConfirmationToken()
						)
					)
				);
			  
				if($this->get('mailer')->send($message))
				{
					$numSent++;
					$to['sent'] = true;
				}
				
			}catch (Swift_SwiftException $e)
			{
			}
			
			//Save every transaction
			$em->flush();
			$toList[] = $to;
		}
		 
		if($numSent > 1)
		{
			$newsletter->setSent(true);
			$em->flush();
		}
		 
		return $this->render('GeckoNewsletterBundle:Backend/Newsletter:send.html.twig', array(
				'newsletter'  => $newsletter,
				'toList'       => $toList,
				'numSent' 	  => $numSent
		));
	}
}