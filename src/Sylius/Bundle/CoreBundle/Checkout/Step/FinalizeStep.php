<?php

namespace Sylius\Bundle\CoreBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\SalesBundle\Model\OrderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Gecko\PigalleBundle\MercadoPago\MP;

/**
 * Final checkout step.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class FinalizeStep extends CheckoutStep
{
    /**
     * {@inheritdoc}
     */
    public function displayAction(ProcessContextInterface $context)
    {
        $order = $this->createOrder($context);       
        
        return $this->renderStep($context, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $order = $this->createOrder($context);
        
        //Save the stock
        $this->getDoctrine()->getManager()->flush();
        
        $this->saveOrder($order);
        $this->getCartProvider()->abandonCart();

        $this->get('session')->set('order_number', $order->getNumber());

        return $this->complete();
    }

    private function renderStep(ProcessContextInterface $context, OrderInterface $order)
    {
    	$user = $this->getUser();
    	$preferenceData = array(
    		"payer" => array(
    			"name" => $user->getShortedName(),
    			"email" => $user->getEmail()
    		),
    		"back_urls" => array(
    			"success" => $this->get('router')->generate('sylius_checkout_forward', array('stepName' => $context->getCurrentStep()->getName()), true),
    			"failure" => $this->get('router')->generate('pigalle_homepage', array(), true),
    			"pending" => $this->get('router')->generate('pigalle_homepage', array(), true)
    		), 
    		"items" => array()    		
    	);
    	
    	/*foreach ($order->getItems() as $item)
    	{
    	$product = $item->getSellable()->getProduct();*/
    		 
    	$preferenceData["items"][] = array(
    			"title" => "Productos Pigalle",
    			"quantity" => 1,
    			"currency_id" => "ARS",
    			"unit_price" => floatval($order->getTotal()/100)
    	);
    	//}
    	
    	/*foreach ($order->getAdjustments() as $adjustment)
    	{
    		$preferenceData["items"][] = array(
    				"title" => "Productos Pigalle",
    				"quantity" => 1,
    				"currency_id" => "ARS",
    				"unit_price" => floatval($adjustment->getAmount()/100)
    		);
    	}*/
    	
    	$mp = new MP('2941808958589998', 'OR3cdSuXJfS4tZlI0N5emDcuyhUXgeRn');
    	$preference = $mp->create_preference($preferenceData);
    	
        return $this->render('PigalleBundle:Checkout/Step:finalize.html.twig', array(
            'context' => $context,
            'order'   => $order,
        	'paylink' => $preference['response']['sandbox_init_point']
        ));
    }

    /**
     * Create order based on the checkout context.
     *
     * @param ProcessContextInterface $context
     *
     * @return OrderInterface
     */
    private function createOrder(ProcessContextInterface $context)
    {
        $orderBuilder = $this->getOrderBuilder();
        $orderBuilder->create();

        $cart = $this->getCurrentCart();

        foreach ($cart->getItems() as $item) {
            $orderBuilder->add($item->getVariant(), $item->getUnitPrice(), $item->getQuantity());
        }

        $order = $orderBuilder->getOrder();

        $order->setUser($this->getUser());

        $order->setshippingAddress($cart->getShippingAddress());
        $order->setBillingAddress($cart->getBillingAddress());

        $this
            ->getInventoryUnitsFactory()
            ->createInventoryUnits($order)
        ;

        $this
            ->getShipmentFactory()
            ->createShipment($order, $cart->getShippingMethod())
        ;
        
        $coupon = $this->getDoctrine()->getRepository('SyliusPromotionsBundle:Coupon')->findOneByCode($cart->getCouponCode());
        if($coupon && $coupon->isValid())
        {
        	$order->setPromotionCoupon($coupon);
        	$coupon->incrementUsed();
        	$this->getDoctrine()->getManager()->flush($coupon);
        }

        $order->calculateTotal();
        $this->get('event_dispatcher')->dispatch('sylius.order.pre_create', new GenericEvent($order));
        $order->calculateTotal();

        return $order;
    }

    /**
     * Save given order.
     *
     * @param OrderInterface $order
     */
    protected function saveOrder(OrderInterface $order)
    {
        $manager = $this->get('sylius.manager.order');

        $manager->persist($order);
        $manager->flush($order);

        $this->get('event_dispatcher')->dispatch('sylius.order.post_create', new GenericEvent($order));
    }

    private function getOrderBuilder()
    {
        return $this->get('sylius.builder.order');
    }

    private function getInventoryUnitsFactory()
    {
        return $this->get('sylius.order_processing.inventory_units_factory');
    }

    private function getShipmentFactory()
    {
        return $this->get('sylius.order_processing.shipment_factory');
    }
}
