<?php

namespace Sylius\Bundle\CoreBundle\Form\Type;

use Sylius\Bundle\TaxonomiesBundle\Form\Type\TaxonType as BaseTaxonType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Taxon form type.
 *
 */
class TaxonType extends BaseTaxonType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder
        	->remove('description')
        	->add('visibleColecciones', null, array(
        		'label' => 'Visible en Colecciones?'
        	))
        	->add('visibleMayoristas', null, array(
        			'label' => 'Visible en Mayoristas?'
        	));
        /*$builder->add(
            'file',
            'file',
            array(
                'label' => 'sylius.form.taxon.file'
            )
        );*/
    }

}
