<?php

namespace Bittich\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Calendrier
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\CalendrierRepository")
 * @UniqueEntity(fields="datej", message="date.unique")
 */
class Calendrier {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datej", type="datetime", unique=true)
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
     * @ORM\Column(name="nbrelitbebe", type="integer")
     */
    private $nbrelitbebe;

    /**
     * @ORM\ManyToMany(targetEntity="Chambre", cascade = "persist", mappedBy="disponibilites")

     */
    protected $chambres;

    public function __construct() {
        $this->setDatej(new \DateTime());
        $this->chambres = new \Doctrine\Common\Collections\ArrayCollection();
        
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set datej
     *
     * @param \DateTime $datej
     * @return Calendrier
     */
    public function setDatej($datej) {
        $this->datej = $datej;

        return $this;
    }

    /**
     * Get datej
     *
     * @return \DateTime 
     */
    public function getDatej() {
        return $this->datej;
    }

   

    /**
     * Set tarif
     *
     * @param \Bittich\HotelBundle\Entity\Tarif $tarif
     * @return Calendrier
     */
    public function setTarif(\Bittich\HotelBundle\Entity\Tarif $tarif) {
        $this->tarif = $tarif;

        return $this;
    }

    /**
     * Get tarif
     *
     * @return \Bittich\HotelBundle\Entity\Tarif 
     */
    public function getTarif() {
        return $this->tarif;
    }

    public function getDateString() {
        $d = '';
        $d.=$this->datej->format('d') . '/';
        $d.=$this->datej->format('m') . '/';
        $d.=$this->datej->format('Y');
        return $d;
    }

    /**
     * Add chambres
     *
     * @param \Bittich\HotelBundle\Entity\Calendrier $chambres
     * @return Calendrier
     */
    public function addChambre(\Bittich\HotelBundle\Entity\Chambre $chambres) {
        $this->chambres[] = $chambres;
     
        return $this;
    }

    /**
     * Remove chambres
     *
     * @param \Bittich\HotelBundle\Entity\Calendrier $chambres
     */
    public function removeChambre(\Bittich\HotelBundle\Entity\Chambre $chambres) {
        $this->chambres->removeElement($chambres);
        return $this;
    }

    /**
     * Get chambres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChambres() {
        return $this->chambres;
    }


    /**
     * Set nbrelitbebe
     *
     * @param integer $nbrelitbebe
     * @return Calendrier
     */
    public function setNbrelitbebe($nbrelitbebe)
    {
        $this->nbrelitbebe = $nbrelitbebe;
    
        return $this;
    }

    /**
     * Get nbrelitbebe
     *
     * @return integer 
     */
    public function getNbrelitbebe()
    {
        return $this->nbrelitbebe;
    }
}