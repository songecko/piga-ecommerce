<?php

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Finder\Finder;
use Gecko\PigalleBundle\Entity\PigalleSlide;

/**
 * Default pigalle slides
 *
 */
class LoadPigalleSlidesData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
        $finder = new Finder();
        $uploader = $this->get('sylius.image_uploader');

        $path = $this->container->getParameter('kernel.root_dir').'/../web/fixtures/slides';
        foreach ($finder->files()->in($path) as $img) {
            $slide = new PigalleSlide();
            $slide->setFile(new UploadedFile($img->getRealPath(), $img->getFilename()));
            $uploader->upload($slide);

            $manager->persist($slide);

            $this->setReference('PigalleSlide.'.$img->getBasename('.jpg'), $slide);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 8;
    }
}
