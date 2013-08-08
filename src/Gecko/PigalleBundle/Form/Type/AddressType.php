<?php

namespace Gecko\PigalleBundle\Form\Type;

use Sylius\Bundle\AddressingBundle\Form\Type\AddressType as BaseAddressType;
use Sylius\Bundle\AddressingBundle\Form\EventListener\BuildAddressFormListener;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Address form type.
 */
class AddressType extends BaseAddressType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		parent::buildForm($builder, $options);
       
		$builder
			->remove('lastName')
			->add('floor', 'text', array(
					'label' => 'sylius.form.address.street_number'
			))
			->add('department', 'text', array(
					'label' => 'sylius.form.address.street_number'
			))
		;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_address';
    }
}
