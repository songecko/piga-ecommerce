<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Sylius\Bundle\CoreBundle\Cart;

use PHPSpec2\ObjectBehavior;

/**
 * Item resolver spec.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ItemResolver extends ObjectBehavior
{
    /**
     * @param Sylius\Bundle\ResourceBundle\Model\RepositoryInterface             $productRepository
     * @param Symfony\Component\Form\FormFactory                                 $formFactory
     * @param Sylius\Bundle\InventoryBundle\Checker\AvailabilityCheckerInterface $availabilityChecker
     * @param Symfony\Component\HttpFoundation\Request                           $request
     */
    function let($productRepository, $formFactory, $availabilityChecker)
    {
        $this->beConstructedWith($productRepository, $formFactory, $availabilityChecker);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Sylius\Bundle\CoreBundle\Cart\ItemResolver');
    }

    function it_implements_Sylius_cart_item_resolver_interface()
    {
        $this->shouldImplement('Sylius\Bundle\CartBundle\Resolver\ItemResolverInterface');
    }

    /**
     * @param Sylius\Bundle\CartBundle\Model\CartItemInterface $item
     */
    function it_throws_exception_unless_request_method_is_POST($item, $request)
    {
        $request->isMethod('POST')->willReturn(false);

        $this
            ->shouldThrow('Sylius\Bundle\CartBundle\Resolver\ItemResolvingException')
            ->duringResolve($item, $request)
        ;
    }

    /**
     * @param Sylius\Bundle\CartBundle\Model\CartItemInterface $item
     */
    function it_throws_exception_when_product_id_is_missing_on_request($item, $request)
    {
        $request->isMethod('POST')->willReturn(true);
        $request->get('id')->willReturn(null);

        $this
            ->shouldThrow('Sylius\Bundle\CartBundle\Resolver\ItemResolvingException')
            ->duringResolve($item, $request)
        ;
    }

    /**
     * @param Sylius\Bundle\CartBundle\Model\CartItemInterface $item
     */
    function it_throws_exception_if_product_with_given_id_does_not_exist($productRepository, $item, $request)
    {
        $request->isMethod('POST')->willReturn(true);
        $request->get('id')->willReturn(5);

        $productRepository->find(5)->willReturn(null);

        $this
            ->shouldThrow('Sylius\Bundle\CartBundle\Resolver\ItemResolvingException')
            ->duringResolve($item, $request)
        ;
    }
}
