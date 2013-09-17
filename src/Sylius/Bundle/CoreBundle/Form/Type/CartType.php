<?php

namespace Sylius\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\CartBundle\Form\Type\CartType as BaseCartType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Cart form form.
 * It is built from collection of cart items forms.
 */
class CartType extends BaseCartType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('items', 'collection', array(
                'type' => 'sylius_cart_item',
            ))
            ->add('couponCode', 'text', array(
                'required' => false,
            ))
        ;
    }
}
