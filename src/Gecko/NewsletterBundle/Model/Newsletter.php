<?php

namespace Gecko\NewsletterBundle\Model;

/**
 * Model for newsletters.
 */
abstract class Newsletter implements NewsletterInterface
{
	const NEWSLETTER_TEMPLATE_NAME_BASIC = "basic";
	const NEWSLETTER_TEMPLATE_NAME_FEATURED = "featured";
	
	public static $NEWSLETTER_TEMPLATE_NAMES = array(
		self::NEWSLETTER_TEMPLATE_NAME_BASIC =>  "Básico",
		self::NEWSLETTER_TEMPLATE_NAME_FEATURED => "Destacados"
	);
	
	public static $NEWSLETTER_TEMPLATE_FILES = array(
			self::NEWSLETTER_TEMPLATE_NAME_BASIC =>  "basic.html.twig",
			self::NEWSLETTER_TEMPLATE_NAME_FEATURED => "featuredProducts.html.twig"
	);
	
    protected $id;
    protected $title;
    protected $templateName;
    protected $subscriberList;
    protected $sent;
    protected $createdAt;
    protected $updatedAt;

    public function __construct()
    {
        $this->sent = false;
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function setTitle($title)
    {
        $this->title = $title;
    }
    
    public function getTemplateName()
    {
        return $this->templateName;
    }
    
    public function getSubscriberList()
    {
    	return $this->subscriberList;	
    }
    
    public function setSubscriberList($subscriberList)
    {
    	$this->subscriberList = $subscriberList;	
    }
    
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }
    
    public function isSent()
    {
        return $this->sent;
    }
    
    public function setSent($sent)
    {
        $this->sent = $sent;
    }
    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }
    
    public function incrementCreatedAt()
    {
        $this->createdAt = new \DateTime();
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    public function incrementUpdatedAt()
    {
        $this->updatedAt = new \DateTime();
    }
}
