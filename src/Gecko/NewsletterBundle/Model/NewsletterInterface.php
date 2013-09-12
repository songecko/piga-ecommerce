<?php

namespace Gecko\NewsletterBundle\Model;

/**
 * Newsletter interface.
 */
interface NewsletterInterface
{
    function getId();
    function getTitle();
    function setTitle($title);
    function getTemplateName();
    function setTemplateName($templateName);
    function getSubscriberList();
    function setSubscriberList($subscriberList);
    function isSent();
    function setSent($sent);
    function incrementCreatedAt();
    function getUpdatedAt();
    function incrementUpdatedAt();
}