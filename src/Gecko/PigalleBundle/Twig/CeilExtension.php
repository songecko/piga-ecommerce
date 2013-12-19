<?php

namespace Gecko\PigalleBundle\Twig;

class CeilExtension extends \Twig_Extension
{
	public function __construct()
	{
	}
	
	public function getFilters()
	{
		return array(
				new \Twig_SimpleFilter('ceil', array($this, 'ceilFilter')),
		);
	}

	public function ceilFilter($number)
	{
		return ceil($number);
	}

	public function getName()
	{
		return 'ceil_extension';
	}
}