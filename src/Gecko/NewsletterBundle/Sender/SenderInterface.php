<?php

namespace Gecko\NewsletterBundle\Sender;

use Gecko\NewsletterBundle\Model\NewsletterInterface;

/**
 * Interface for newsletter sender.
 */
interface SenderInterface
{
    function send(NewsletterInterface $newsletter);
    function supports(NewsletterInterface $newsletter);
}
