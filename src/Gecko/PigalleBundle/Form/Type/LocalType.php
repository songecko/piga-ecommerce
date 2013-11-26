<?php

namespace Gecko\PigalleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Gecko\PigalleBundle\Entity\Local;

/**
 * Local form type.
 */
class LocalType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$builder
			->add('name', 'text', array(
	        	'required' => true,
	        	'label'    => 'Nombre'
        	))
			->add('address', 'text', array(
	        	'required' => true,
	        	'label'    => 'Dirección Linea 1'
        	))
			->add('address2', 'text', array(
        		'required' => false,
        		'label'    => 'Dirección Linea 2'
        	))        	
			->add('location', 'choice', array(
	        	'choices'   => Local::$LOCATIONS,
	        	'required'  => true,
	        	'label'     => 'Lugar'
        	))
			->add('phone', 'text', array(
        		'required' => true,
        		'label'    => 'Teléfono'
        	))
			->add('email', 'text', array(
        		'required' => true,
        		'label'    => 'Email'
        	))
        	->add('isFeatured', null, array(
				'label'    => 'Destacado?'
			));
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sylius_local';
    }
}
