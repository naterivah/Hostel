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
// Je persiste en cascade la date, puisque je mettrai à jour les disponibilités
    /**
     * @ORM\ManyToMany(targetEntity="Calendrier", cascade = "persist", inversedBy="chambres")
             * @ORM\JoinTable(name="disponibilites")
     */
    protected $disponibilites;

    /**
     * Constructor
     */

    /**
     * Consctruct
     */
    public function __construct() {
        $this->setEtage(0);
        $this->disponibilites = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function setModele(\Bittich\HotelBundle\Entity\Modele $modele = null) {
        $this->modele = $modele;

        return $this;
    }

    /**
     * Get modele
     *
     * @return \Bittich\HotelBundle\Entity\Modele 
     */
    public function getModele() {
        return $this->modele;
    }

    /**
     * Add disponibilites
     *
     * @param \Bittich\HotelBundle\Entity\Calendrier $disponibilites
     * @return Chambre
     */
    public function addDisponibilite(\Bittich\HotelBundle\Entity\Calendrier $disponibilites) {
        $disponibilites->addChambre($this);
        $this->disponibilites[] = $disponibilites;
        return $this;
    }

    /**
     * Remove disponibilites
     *
     * @param \Bittich\HotelBundle\Entity\Calendrier $disponibilites
     */
    public function removeDisponibilite(\Bittich\HotelBundle\Entity\Calendrier $disponibilites) {
        $this->disponibilites->removeElement($disponibilites);
    }

    /**
     * Get disponibilites
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDisponibilites() {
        return $this->disponibilites;
    }
    

}