<?php

namespace Gecko\PigalleBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Product form type.
 */
class ProductCollectionType extends AbstractType
{	
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
        	->add('name', 'text', array(
        		'label' => 'sylius.label.product.name'
        	))
        	->add('description', 'textarea', array(
        		'label' => 'sylius.label.product.description'
        	))
            ->add('shortDescription', 'textarea', array(
                'required' => false,
                'label'    => 'sylius.form.product.short_description'
            ))
            ->add('images', 'collection', array(
            		'type'         => 'sylius_product_collection_image',
            		'allow_add'    => true,
            		'allow_delete' => true,
            		'by_reference' => false,
            		'label'        => 'sylius.form.variant.images'
            ))
            ->add('taxons', 'sylius_taxon_selection')
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'sylius_product_collection';
    }
}
