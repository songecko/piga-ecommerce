<?php

namespace Gecko\NewsletterBundle\Sender;

use Gecko\NewsletterBundle\Model\NewsletterInterface;

class BasicSender implements SenderInterface
{        
    public function send(NewsletterInterface $newsletter)
    {
        if ($this->supports($newsletter)) 
        {
		}
    }
    
    public function supports(NewsletterInterface $newsletter)
    {
        return true;
    }
}
