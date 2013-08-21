<?php

namespace Gecko\PigalleBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Collection;

class ContactType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('name', 'text', array())
			->add('email', 'email', array())
			->add('phone', 'text', array(
				'required' => false
			))
			->add('message', 'textarea', array());
	}

	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$collectionConstraint = new Collection(array(
				'name' => array(
						new NotBlank(array('message' => 'Debes ingresar tu nombre')),
						new Length(array('min' => 2))
				),
				'email' => array(
						new NotBlank(array('message' => 'Debes ingresar tu email')),
						new Email(array('message' => 'Direccion de email incorrecto'))
				),
				'phone' => array(),
				'message' => array(
						new NotBlank(array('message' => 'El campo mensaje es obligatorio.')),
						new Length(array('min' => 5))
				)
		));

		$resolver->setDefaults(array(
				'constraints' => $collectionConstraint
		));
	}

	public function getName()
	{
		return 'contact';
	}
}