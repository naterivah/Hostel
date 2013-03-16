<?php

namespace Bittich\HotelBundle\Entity;
//use Bittich\HotelBundle\Entity\Modele;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Chambre
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\ChambreRepository")
 */
class Chambre {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="etage", type="integer")
     */
    private $etage;

    /**
     * @var boolean
     *
     * @ORM\Column(name="litbebe", type="boolean")
     */
    private $litbebe;

    /**
     * @ORM\ManyToOne(targetEntity="Modele")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "erreur.champs.vide")
     */
    protected $modele;
    /**
     * Consctruct
     */
    public function __construct() {
        $this->setEtage(0);
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
     * Set etage
     *
     * @param integer $etage
     * @return Chambre
     */
    public function setEtage($etage) {
        $this->etage = $etage;

        return $this;
    }

    /**
     * Get etage
     *
     * @return integer 
     */
    public function getEtage() {
        return $this->etage;
    }

    /**
     * Set litbebe
     *
     * @param boolean $litbebe
     * @return Chambre
     */
    public function setLitbebe($litbebe) {
        $this->litbebe = $litbebe;

        return $this;
    }

    /**
     * Get litbebe
     *
     * @return boolean 
     */
    public function getLitbebe() {
        return $this->litbebe;
    }


    /**
     * Set modele
     *
     * @param \Bittich\HotelBundle\Entity\Modele $modele
     * @return Chambre
     */
    public function setModele(\Bittich\HotelBundle\Entity\Modele $modele = null)
    {
        $this->modele = $modele;
    
        return $this;
    }

    /**
     * Get modele
     *
     * @return \Bittich\HotelBundle\Entity\Modele 
     */
    public function getModele()
    {
        return $this->modele;
    }
}