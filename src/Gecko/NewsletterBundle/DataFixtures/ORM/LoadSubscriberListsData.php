<?php

namespace Gecko\NewsletterBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Gecko\NewsletterBundle\Entity\SubscriberList;
use Sylius\Bundle\CoreBundle\DataFixtures\ORM\DataFixture;

/**
 * Default subscriber lists
 */
class LoadSubscriberListsData extends DataFixture
{
	private $listNumber = 0;
	
    public function load(ObjectManager $manager)
    {
		$subscriberList = $this->getSubscriberListWithNameNumber();
		
        for ($i = 1; $i <= 30; $i++) {
        	$subscriber = $this->getReference('Gecko.Newsletter.Subscriber-'.$i);
            $subscriberList->addSubscriber($subscriber);
			
            if(rand(1, 10) == 1 || $i == 30)
            {
	            $manager->persist($subscriberList);
	            $this->setReference('Gecko.Newsletter.SubscriberList-'.$this->listNumber, $subscriberList);
	            
	            if($i < 30)
	            {
		            $subscriberList = $this->getSubscriberListWithNameNumber();
	            }
            }
        }

        $manager->flush();
    }

    public function getSubscriberListWithNameNumber()
    {
    	$this->listNumber++;
    	$subscriberList = new SubscriberList();
    	$subscriberList->setName("Lista ".$this->listNumber);
    	
    	return $subscriberList;
    }
    
    public function getOrder()
    {
        return 2;
    }
}
