<?php

namespace Gecko\NewsletterBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Subscriber form type.
 */
class SubscriberListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('name', 'text', array(
        		'required' => true,
        		'label'    => 'Nombre'
        ))
        ->add('subscribers', 'entity', array(
        		'class' => 'Gecko\NewsletterBundle\Entity\Subscriber',
        		'property'     => 'email',
        		'multiple'     => true,
        		'label'    => 'Suscriptores'
        ));
    }

    public function getName()
    {
        return 'gecko_newsletter_subscriber_list';
    }
}
