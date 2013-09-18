<?php

namespace Gecko\PigalleBundle\Entity;

use Sylius\Bundle\CoreBundle\Model\Image;

class ProductCollectionImage extends Image
{
	protected $productCollection;

	public function getProductCollection()
	{
		return $this->productCollection;
	}

	public function setProductCollection($productCollection)
	{
		$this->productCollection = $productCollection;
	}
}