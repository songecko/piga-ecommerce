<?php

namespace Gecko\NewsletterBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Subscriber form type.
 */
class SubscriberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('fullname', 'text', array(
        		'required' => false,
        		'label'    => 'Nombre'
        ))
        ->add('email', 'email', array(
        		'required' => true,
        		'label'    => 'Email'
        ))
        ->add('enabled', null, array(
        		'label'    => 'Activo?'
        ));
    }

    public function getName()
    {
        return 'gecko_newsletter_subscriber';
    }
}
