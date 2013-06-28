<?php

namespace Gecko\PigalleBundle\Twig;

class PriceExtension extends \Twig_Extension
{
	public function __construct()
	{
	}
	
	public function getFilters()
	{
		return array(
				new \Twig_SimpleFilter('price', array($this, 'priceFilter')),
		);
	}

	public function priceFilter($number, $decimals = 2, $decPoint = '.', $thousandsSep = ',')
	{
		$price = number_format($number/100, $decimals, $decPoint, $thousandsSep);
		$price = '$'.$price;

		return $price;
	}

	public function getName()
	{
		return 'price_extension';
	}
}