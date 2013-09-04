<?php

namespace Gecko\PigalleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PigalleSlideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', 'text', array(
        		'required' => false,
        		'label'    => 'Título'
        ))
        ->add('text', 'textarea', array(
        		'required' => false,
        		'label'    => 'Texto'
        ))
        ->add('file', 'file', array(
            'label' => 'Imagen'
        ))
        ->add('linkText', 'text', array(
        		'required' => false,
        		'label'    => 'Texto del link'
        ))
        ->add('linkUrl', 'text', array(
        		'required' => false,
        		'label'    => 'Url del link'
        ))
        ->add('isActive', null, array(
        		'label'    => 'Activo?'
        ));
    }

    public function getName()
    {
        return 'sylius_pigalle_slide';
    }
}
