<?php

namespace Gecko\PigalleBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Finder\Finder;
use Sylius\Bundle\CoreBundle\DataFixtures\ORM\DataFixture;
use Gecko\PigalleBundle\Entity\ProductCollectionImage;

/**
 * Default product images.
 */
class LoadProductCollectionImagesData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        $path = $this->container->getParameter('kernel.root_dir').'/../web/fixtures';
        foreach ($finder->files()->in($path) as $img) {
            $image = new ProductCollectionImage();
            $image->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
            $uploader->upload($image);

            $manager->persist($image);

            $this->setReference('Pigalle.ProductCollectionImage.'.$img->getBasename('.jpg'), $image);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 15;
    }
}
