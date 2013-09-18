<?php

namespace Gecko\PigalleBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Product image form type.
 */
class ProductCollectionImageType extends AbstractType
{	
	protected $dataClass;
	
	public function __construct($dataClass)
	{
		$this->dataClass = $dataClass;
	}
	
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('file', 'file', array(
            'label' => 'sylius.form.image.file'
        ));
    }
    
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
    	$resolver
    	->setDefaults(array(
    			'data_class' => $this->dataClass
    	))
    	;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
    	return 'sylius_product_collection_image';
    }
}
