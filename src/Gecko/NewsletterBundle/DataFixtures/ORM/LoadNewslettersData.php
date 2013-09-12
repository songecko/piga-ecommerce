<?php

namespace Gecko\NewsletterBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Gecko\NewsletterBundle\Entity\Newsletter;
use Sylius\Bundle\CoreBundle\DataFixtures\ORM\DataFixture;

/**
 * Default newsletters
 */
class LoadNewslettersData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
		$newsletter = new Newsletter();
        $newsletter->setTitle("Newsletter 1");
        $newsletter->setTemplateName(Newsletter::NEWSLETTER_TEMPLATE_NAME_BASIC);
		$newsletter->setSubscriberList($this->getReference('Gecko.Newsletter.SubscriberList-1'));

		$manager->persist($newsletter);

		$this->setReference('Gecko.Newsletter.Newsletter-1', $newsletter);
		
        $manager->flush();
    }

    public function getOrder()
    {
        return 3;
    }
}
