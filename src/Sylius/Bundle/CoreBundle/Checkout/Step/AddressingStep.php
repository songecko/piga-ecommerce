<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Symfony\Component\Form\FormInterface;
use Gecko\PigalleBundle\Entity\Address;

/**
 * The addressing step of checkout.
 * User enters the shipping and shipping address.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class AddressingStep extends CheckoutStep
{
	private $defaultCountry;
	
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $form = $this->createCheckoutAddressingForm();

        return $this->renderStep($context, $form);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $request = $this->getRequest();
        $form = $this->createCheckoutAddressingForm();

        if ($request->isMethod('POST') && $form->bind($request)->isValid()) {
            $this->getManager()->persist($this->getCurrentCart());
            $this->getManager()->flush();

            return $this->complete();
        }

        return $this->renderStep($context, $form);
    }

    private function renderStep(ProcessContextInterface $context, FormInterface $form)
    {
        return $this->render('PigalleBundle:Checkout/Step:addressing.html.twig', array(
            'form'    => $form->createView(),
            'context' => $context
        ));

    }

    private function createCheckoutAddressingForm()
    {       	
    	$cart = $this->getCurrentCart();
    	
    	$shippingAddress = $this->checkOrCreateDefaultCountry($cart->getShippingAddress());    	 	
    	$cart->setShippingAddress($shippingAddress);
    	
    	$billingAddress = $this->checkOrCreateDefaultCountry($cart->getBillingAddress());
    	$cart->setBillingAddress($billingAddress);    	
    	
        return $this->createForm('sylius_checkout_addressing', $this->getCurrentCart());
    }
    
    private function checkOrCreateDefaultCountry($address)
    {
    	$country = $this->getDefaultCountry();
    	return $this->checkOrCreateCountry($address, $country);
    }
    
    private function checkOrCreateCountry($address, $country)
    {    	
    	if(!$address)
    	{
    		$address = new Address();
    	}    	
    	
    	if(!$address->getCountry($country))
    	{
    		$address->setCountry($country);   		
    	}

    	return $address;
    }
    
    private function getDefaultCountry()
    {
    	if(!$this->defaultCountry)
    		$this->defaultCountry = $this->get('sylius.repository.country')->findOneByIsoName('AR');
    	
    	return $this->defaultCountry;
    }
}
