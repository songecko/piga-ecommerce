<?php

namespace Gecko\NewsletterBundle\Model;

/**
 * Subscriber list interface.
 */
interface SubscriberListInterface
{
    function getId();
    function getName();
    function setName($name);
    function hasSubscriber(SubscriberInterface $subscriber);
    function addSubscriber(SubscriberInterface $subscriber);
    function removeSubscriber(SubscriberInterface $subscriber);
    function getCreatedAt();
    function getUpdatedAt();
}