<?php

namespace Gecko\NewsletterBundle\Model;

use SplFileInfo;
use DateTime;
use Sylius\Bundle\CoreBundle\Model\ImageInterface;

/**
 * Model for newsletters.
 */
abstract class Newsletter implements NewsletterInterface, ImageInterface
{
	const NEWSLETTER_TEMPLATE_NAME_BASIC = "basic";
	const NEWSLETTER_TEMPLATE_NAME_FEATURED = "featured";
	const NEWSLETTER_TEMPLATE_NAME_SPECIAL = "special";
	
	public static $NEWSLETTER_TEMPLATE_NAMES = array(
		self::NEWSLETTER_TEMPLATE_NAME_BASIC =>  "Básico",
		self::NEWSLETTER_TEMPLATE_NAME_FEATURED => "Destacados",
		self::NEWSLETTER_TEMPLATE_NAME_SPECIAL => "Especial"
	);
	
	public static $NEWSLETTER_TEMPLATE_FILES = array(
		self::NEWSLETTER_TEMPLATE_NAME_BASIC =>  "basic.html.twig",
		self::NEWSLETTER_TEMPLATE_NAME_FEATURED => "featuredProducts.html.twig",
		self::NEWSLETTER_TEMPLATE_NAME_SPECIAL => "special.html.twig"
	);
	
    protected $id;
    protected $title;
    protected $introText;
    protected $coupon;
    protected $file;
	protected $path;
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
    
    public function getIntroText()
    {
    	return $this->introText;
    }
    
    public function setIntroText($introText)
    {
    	$this->introText = $introText;
    }
    
    public function getCoupon()
    {
    	return $this->coupon;
    }
    
    public function setCoupon($coupon)
    {
    	$this->coupon = $coupon;
    }
    
    public function hasFile()
    {
    	return null !== $this->file;
    }
    
    public function getFile()
    {
    	return $this->file;
    }
    
    public function setFile(SplFileInfo $file)
    {
    	$this->file = $file;
    }
    
    public function hasPath()
    {
    	return null !== $this->path;
    }
    
    public function getPath()
    {
    	return $this->path;
    }
    
    public function setPath($path)
    {
    	$this->path = $path;
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
    
    public function setCreatedAt(DateTime $createdAt)
    {
    	$this->createdAt = $createdAt;
    }
    
    public function setUpdatedAt(DateTime $updatedAt)
    {
    	$this->updatedAt = $updatedAt;
    }
}
