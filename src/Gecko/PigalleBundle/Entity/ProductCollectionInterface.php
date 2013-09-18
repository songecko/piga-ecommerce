<?php

namespace Gecko\PigalleBundle\Entity;

use Sylius\Bundle\ResourceBundle\Model\SoftDeletableInterface;
use Sylius\Bundle\ResourceBundle\Model\TimestampableInterface;

/**
 * Basic product interface.
 */
interface ProductCollectionInterface extends TimestampableInterface
{
    public function getName();
    public function setName($name);
    public function getSlug();
    public function setSlug($slug);
    public function getDescription();
    public function setDescription($description);
    public function getShortDescription();
    public function setShortDescription($shortDescription);
}
