<?php

namespace Gecko\UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * User admin type.
 */
class UserAdminType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('first_name', 'text', array(
        		'label' => 'sylius.form.user.first_name'
        	))
            ->add('email', 'text', array(
                'label' => 'sylius.form.user.email'
            ))
            ->add('enabled', 'checkbox', array(
            		'label' => 'sylius.form.user.enabled'
            ))
        ;
    }
    
    public function getName()
    {
    	return 'fos_user_admin';
    }
}
