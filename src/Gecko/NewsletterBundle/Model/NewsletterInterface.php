<?php

namespace Gecko\NewsletterBundle\Model;

/**
 * Newsletter interface.
 */
interface NewsletterInterface
{
    function getTitle();
    function setTitle($title);
    function getIntroText();
    function setIntroText($introText);
    function getCoupon();
    function setCoupon($coupon);
    function getTemplateName();
    function setTemplateName($templateName);
    function getSubscriberList();
    function setSubscriberList($subscriberList);
    function isSent();
    function setSent($sent);
    function incrementCreatedAt();
    function incrementUpdatedAt();
}