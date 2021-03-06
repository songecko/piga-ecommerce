<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Paweł Jędrzejewski
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sylius\Bundle\CoreBundle\EventListener;

use Sylius\Bundle\CoreBundle\Uploader\ImageUploaderInterface;
use Sylius\Bundle\TaxonomiesBundle\Model\TaxonInterface;
use Sylius\Bundle\TaxonomiesBundle\Model\TaxonomyInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Sylius\Bundle\AssortmentBundle\Model\CustomizableProductInterface;
use Sylius\Bundle\AssortmentBundle\Model\Variant\VariantInterface;
use Sylius\Bundle\CoreBundle\Model\ImageInterface;
use Gecko\PigalleBundle\Entity\ProductCollectionImage;
use Gecko\PigalleBundle\Entity\ProductCollection;
use Gecko\NewsletterBundle\Entity\Newsletter;

class ImageUploadListener
{
    protected $uploader;

    public function __construct(ImageUploaderInterface $uploader)
    {
        $this->uploader = $uploader;
    }

    public function uploadProductImage(GenericEvent $event)
    {
        $subject = $event->getSubject();
        if (!$subject instanceof CustomizableProductInterface && !$subject instanceof VariantInterface){
            throw new \InvalidArgumentException('CustomizableProductInterface or VariantInterface expected.');
        }

        $variant = $subject instanceof VariantInterface ? $subject : $subject->getMasterVariant();

        foreach ($variant->getImages() as $image) {
            if (null === $image->getId()) {
                $this->uploader->upload($image);
            }
        }
    }

    public function uploadTaxonImage(GenericEvent $event)
    {
        $subject = $event->getSubject();

        if (!$subject instanceof TaxonInterface){
            throw new \InvalidArgumentException('TaxonInterface expected.');
        }

        if ($subject->hasFile()) {
            $this->uploader->upload($subject);
        }

    }

    public function uploadTaxonomyImage(GenericEvent $event)
    {
        $subject = $event->getSubject();

        if (!$subject instanceof TaxonomyInterface){
            throw new \InvalidArgumentException('TaxonomyInterface expected.');
        }

        if ($subject->getRoot()->hasFile()) {
            $this->uploader->upload($subject->getRoot());
        }

    }
    
    public function uploadPigalleSlideImage(GenericEvent $event)
    {
    	$subject = $event->getSubject();
    
    	if (!$subject instanceof ImageInterface){
    		throw new \InvalidArgumentException('ImageInterface expected.');
    	}
    
    	if ($subject->hasFile()) {
    		$this->uploader->upload($subject);
    	}
    }
    
    public function uploadProductCollectionImage(GenericEvent $event)
    {
    	$subject = $event->getSubject();
    
    	if (!$subject instanceof ProductCollection){
    		throw new \InvalidArgumentException('ProductCollection expected.');
    	}
    	
    	foreach ($subject->getImages() as $image) {
    		if (null === $image->getId()) {
    			$this->uploader->upload($image);
    		}
    	}
    }
    
    public function uploadNewsletterImage(GenericEvent $event)
    {
    	$subject = $event->getSubject();
    
    	if (!$subject instanceof Newsletter){
    		throw new \InvalidArgumentException('Newsletter expected.');
    	}
    	 
    	if ($subject->hasFile()) {
    		$this->uploader->upload($subject);
    	}
    }
}
