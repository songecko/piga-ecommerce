<?php

namespace Gecko\NewsletterBundle\Model;

/**
 * Model for subscribers.
 */
abstract class Subscriber implements SubscriberInterface
{
    protected $id;
    protected $fullname;
    protected $email;
    protected $enabled;
    protected $confirmationToken;
    protected $createdAt;
    protected $updatedAt;

    public function __construct()
    {
        $this->enabled = false;
        $this->generateConfirmationToken();
    }
    
    public function getId()
    {
        return $this->id;
    }
    
    public function getFullname()
    {
    	return $this->fullname;
    }
    
    public function setFullname($fullname)
    {
    	$this->fullname = $fullname;
    }
    
    public function getEmail()
    {
        return $this->email;
    }
    
    public function setEmail($email)
    {
        $this->email = strtolower($email);
    }
    
    public function isEnabled()
    {
        return $this->enabled;
    }
    
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    
	/**
     * Get confirmation token.
     * 
     * @return string
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }
    
	/**
     * Set confirmation token.
     *
     * @param string
     */
    public function setConfirmationToken($confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * Generate confirmation token if it is not set.
     */
    public function generateConfirmationToken()
    {
        if (null === $this->confirmationToken) {
            $bytes = false;
            if (function_exists('openssl_random_pseudo_bytes') && 0 !== stripos(PHP_OS, 'win')) {
                $bytes = openssl_random_pseudo_bytes(32, $strong);

                if (true !== $strong) {
                    $bytes = false;
                }
            }

            if (false === $bytes) {
                $bytes = hash('sha256', uniqid(mt_rand(), true), true);
            }

            $this->confirmationToken = base_convert(bin2hex($bytes), 16, 36);
        }
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
    
    public function __toString()
    {
    	return $this->getEmail();
    }
    
    public function getSomeName()
    {
    	$name = $this->getFullname();
    	 
    	if(!$name)
    	{
    		$arr = explode("@", $this->getEmail());
    		$name = $arr[0];
    	}
    	 
    	return $name;
    }
}
