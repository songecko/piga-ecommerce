<?php

namespace Gecko\NewsletterBundle\Form\Type;

use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Gecko\NewsletterBundle\Entity\Newsletter;

/**
 * Newsletter form type.
 */
class NewsletterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', 'text', array(
        	'required' => true,
        	'label'    => 'Titulo'
        ))
        ->add('introText', 'text', array(
        	'required' => false,
        	'label'    => 'Texto introductorio'
        ))
        ->add('coupon', 'text', array(
        	'required' => false,
        	'label'    => 'Cupon'
        ))
        ->add('file', 'file', array(
        	'label' => 'Imagen'
        ))
        ->add('templateName', 'choice', array(
        	'choices'   => Newsletter::$NEWSLETTER_TEMPLATE_NAMES,
        	'required'  => true,
        	'label'     => 'Template'
        ))
        ->add('subscriberList', 'entity', array(
        	'class' => 'Gecko\NewsletterBundle\Entity\SubscriberList',
        	'property'  => 'name',
        	'required' => false,
        	'empty_value' => 'Todos',
        	'multiple'  => false,
        	'label'     => 'Lista de envio'
        ));
    }

    public function getName()
    {
        return 'gecko_newsletter_newsletter';
    }
}
