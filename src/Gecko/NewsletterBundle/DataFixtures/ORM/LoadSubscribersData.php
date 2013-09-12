<?php

namespace Gecko\NewsletterBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Gecko\NewsletterBundle\Entity\Subscriber;
use Sylius\Bundle\CoreBundle\DataFixtures\ORM\DataFixture;

/**
 * Default subscribers
 */
class LoadSubscribersData extends DataFixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 30; $i++) {
            $subscriber = new Subscriber();
            $username = $this->faker->username;

            $subscriber->setFullname($username);
            $subscriber->setEmail($username.'@example.com');
            $subscriber->setEnabled($this->faker->boolean());

            $manager->persist($subscriber);

            $this->setReference('Gecko.Newsletter.Subscriber-'.$i, $subscriber);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 1;
    }
}
