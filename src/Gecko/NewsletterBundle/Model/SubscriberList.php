<?php

namespace Gecko\NewsletterBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Model for subscriber lists.
 */
abstract class SubscriberList implements SubscriberListInterface
{
    protected $id;
    protected $name;
    protected $subscribers;
    protected $createdAt;
    protected $updatedAt;

    public function __construct()
    {
        $this->subscribers = new ArrayCollection();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getName()
    {
    	return $this->name;
    }
    
    public function setName($name)
    {
    	$this->name = $name;
    }
    
    public function getSubscribers()
    {
        return $this->subscribers;
    }
    
	public function hasSubscriber(SubscriberInterface $subscriber)
    {
        return $this->subscribers->contains($subscriber);
    }

    public function addSubscriber(SubscriberInterface $subscriber)
    {
        if (!$this->hasSubscriber($subscriber)) 
        {
            $this->subscribers->add($subscriber);
        }
    }

    public function removeSubscriber(SubscriberInterface $subscriber)
    {
        $this->subscribers->removeElement($subscriber);
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}
