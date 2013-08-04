<?php

namespace Gecko\PigalleBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

/**
 * Profile form.
 *
 * @author Julien Janvier <j.janvier@gmail.com>
 */
class ProfileFormType extends BaseType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        	->add('firstName', 'text', array(
        		'label'    => 'Nombre',
        		'required' => false
        	))
            ->add('email', 'email', array(
                'label'    => 'Email'
            ))
        ;
    }

    public function getName()
    {
        return 'sylius_user_profile';
    }
}
