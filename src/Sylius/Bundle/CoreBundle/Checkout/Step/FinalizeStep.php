 <?php

namespace Sylius\Bundle\CoreBundle\Checkout\Step;

use Sylius\Bundle\FlowBundle\Process\Context\ProcessContextInterface;
use Sylius\Bundle\SalesBundle\Model\OrderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

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

        /*$preference_data = array(
        	"items" => array(
        		array(
        			"title" => "Barrilete multicolor",
        			"quantity" => 1,
        			"currency_id" => "ARS",
        			"unit_price" => 10.00
        		)
        	)
        );
        
        $mp = new MP('2941808958589998', 'OR3cdSuXJfS4tZlI0N5emDcuyhUXgeRn');
        $preference = $mp->create_preference($preference_data);
        ldd($preference['response']['init_point']);*/
        return $this->renderStep($context, $order);
    }

    /**
     * {@inheritdoc}
     */
    public function forwardAction(ProcessContextInterface $context)
    {
        $order = $this->createOrder($context);

        $this->saveOrder($order);
        $this->getCartProvider()->abandonCart();

        $this->get('session')->set('order_number', $order->getNumber());

        return $this->complete();
    }

    private function renderStep(ProcessContextInterface $context, OrderInterface $order)
    {
        return $this->render('PigalleBundle:Checkout/Step:finalize.html.twig', array(
            'context' => $context,
            'order'   => $order
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
