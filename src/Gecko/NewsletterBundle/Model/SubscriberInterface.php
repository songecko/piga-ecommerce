<?php

namespace Gecko\NewsletterBundle\Model;

/**
 * Subscriber interface.
 */
interface SubscriberInterface
{
    function getId();
    function getFullname();
    function setFullname($fullname);
    function getEmail();
    function setEmail($email);
    function isEnabled();
    function setEnabled($enabled);
    function getConfirmationToken();
    function setConfirmationToken($confirmationToken);
    function getCreatedAt();
    function incrementCreatedAt();
    function getUpdatedAt();
    function incrementUpdatedAt();
}