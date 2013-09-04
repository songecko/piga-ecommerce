<?php

namespace Gecko\PigalleBundle\Entity;

use SplFileInfo;
use DateTime;
use Sylius\Bundle\CoreBundle\Model\ImageInterface;

/**
 * PigalleSlide entity.
 */
class PigalleSlide implements ImageInterface
{
	protected $id;
	protected $file;
	protected $path;
	protected $title;
	protected $text;
	protected $linkText;
	protected $linkUrl;
	protected $isActive;
	protected $createdAt;
	protected $updatedAt;
	
	public function __construct()
	{
		$this->createdAt = new DateTime('now');
		$this->isActive = true;
	}
	
	public function getId()
	{
		return $this->id;
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
	
	public function hasTitle()
	{
		return $this->title !== null;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	public function hasText()
	{
		return $this->text !== null;
	}
	
	public function getText()
	{
		return $this->text;
	}
	
	public function setText($text)
	{
		$this->text = $text;
	}
	
	public function getLinkText()
	{
		return $this->linkText;
	}
	
	public function setLinkText($linkText)
	{
		$this->linkText = $linkText;
	}
	
	public function getLinkUrl()
	{
		return $this->linkUrl;
	}
	
	public function setLinkUrl($linkUrl)
	{
		$this->linkUrl = $linkUrl;
	}
	
	public function getIsActive()
	{
		return $this->isActive;
	}
	
	public function setIsActive($isActive)
	{
		$this->isActive = $isActive;
	}
	
	public function getCreatedAt()
	{
		return $this->createdAt;
	}
	
	public function setCreatedAt(DateTime $createdAt)
	{
		$this->createdAt = $createdAt;
	}
	
	public function getUpdatedAt()
	{
		return $this->updatedAt;
	}
	
	public function setUpdatedAt(DateTime $updatedAt)
	{
		$this->updatedAt = $updatedAt;
	}
	
	public function hasBox()
	{
		return ($this->hasTitle() || $this->hasText());
	}
	
	public function hasLink()
	{
		return ($this->linkUrl !== null && $this->linkText !== null);
	}
}
