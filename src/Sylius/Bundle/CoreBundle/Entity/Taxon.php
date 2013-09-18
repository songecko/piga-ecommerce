<?php

namespace Sylius\Bundle\CoreBundle\Entity;

use Sylius\Bundle\CoreBundle\Model\ImageInterface;
use Sylius\Bundle\TaxonomiesBundle\Entity\Taxon as BaseTaxon;
use SplFileInfo;
use DateTime;

/**
 * Sylius core taxon entity.
 *
 */
class Taxon extends BaseTaxon implements ImageInterface
{

    /**
     * @var SplFileInfo
     */
    protected $file;

    /**
     * @var string
     */
    protected $path;

    protected $visibleColecciones;
    protected $visibleMayoristas;
    
    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    const TAXON_SEASON_SUMMER = 'Primavera - Verano 2013';
    const TAXON_SEASON_WINTER = 'Otoño - Invierno 2014';
    
    public function __construct()
    {
        parent::__construct();

        $this->visibleColecciones = false;
        $this->visibleMayoristas = true;
        $this->createdAt = new DateTime();
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

    public function isVisibleMayoristas()
    {
    	return $this->visibleMayoristas !== false;
    }
    
    public function getVisibleMayoristas()
    {
    	return $this->visibleMayoristas;
    }
    
    public function setVisibleMayoristas($visibleMayoristas)
    {
    	$this->visibleMayoristas = $visibleMayoristas;
    }
    
    public function isVisibleColecciones()
    {
    	return $this->visibleColecciones !== false;
    }
    
    public function getVisibleColecciones()
    {
    	return $this->visibleColecciones;
    }
    
    public function setVisibleColecciones($visibleColecciones)
    {
    	$this->visibleColecciones = $visibleColecciones;
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

}