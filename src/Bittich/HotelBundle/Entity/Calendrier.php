<?php

namespace Bittich\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Calendrier
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\CalendrierRepository")
 */
class Calendrier
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datej", type="datetime")
     * @ORM\Id
     * @Assert\NotBlank(message = "erreur.champs.vide")
     */
    private $datej;
    
     /**
     * @ORM\ManyToOne(targetEntity="Tarif")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "erreur.champs.vide")
     */
    protected $tarif;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbredispo", type="integer")
     */
    private $nbredispo;

    public function __construct(){
        $this->setNbredispo(0);
    }
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set datej
     *
     * @param \DateTime $datej
     * @return Calendrier
     */
    public function setDatej($datej)
    {
        $this->datej = $datej;
    
        return $this;
    }

    /**
     * Get datej
     *
     * @return \DateTime 
     */
    public function getDatej()
    {
        return $this->datej;
    }

    /**
     * Set nbredispo
     *
     * @param integer $nbredispo
     * @return Calendrier
     */
    public function setNbredispo($nbredispo)
    {
        $this->nbredispo = $nbredispo;
    
        return $this;
    }

    /**
     * Get nbredispo
     *
     * @return integer 
     */
    public function getNbredispo()
    {
        return $this->nbredispo;
    }

    /**
     * Set tarif
     *
     * @param \Bittich\HotelBundle\Entity\Tarif $tarif
     * @return Calendrier
     */
    public function setTarif(\Bittich\HotelBundle\Entity\Tarif $tarif)
    {
        $this->tarif = $tarif;
    
        return $this;
    }

    /**
     * Get tarif
     *
     * @return \Bittich\HotelBundle\Entity\Tarif 
     */
    public function getTarif()
    {
        return $this->tarif;
    }
}