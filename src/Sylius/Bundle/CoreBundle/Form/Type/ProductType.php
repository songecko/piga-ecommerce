<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\AssortmentBundle\Form\Type\CustomizableProductType as BaseProductType;
use Sylius\Bundle\CoreBundle\Entity\Product;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Product form type.
 *
 * @author Paweł Jędrzejewski <pjedrzejewski@diweb.pl>
 */
class ProductType extends BaseProductType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
        	->add('slug', 'text', array(
        		'label' => 'Url única'
        	))
            ->add('shortDescription', 'textarea', array(
                'required' => false,
                'label'    => 'sylius.form.product.short_description'
            ))
            ->add('priceWithoutDiscount', 'sylius_money', array(
                'label' => 'sylius.form.product.price_without_discount'
            ))
            ->add('isFeatured', null, array(
            	'label' => 'sylius.form.product.is_featured'		
        	))
            /*->add('taxCategory', 'sylius_tax_category_choice', array(
                'required'    => false,
                'empty_value' => '---',
                'label'       => 'sylius.form.product.tax_category'
            ))*/
            ->add('taxons', 'sylius_taxon_selection')
            /*->add('variantSelectionMethod', 'choice', array(
                'label'   => 'sylius.form.product.variant_selection_method',
                'choices' => Product::getVariantSelectionMethodLabels()
            ))*/
        ;
    }
}
