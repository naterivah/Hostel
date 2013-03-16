<?php

namespace Bittich\HotelBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * Modele
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\ModeleRepository")
 */
class Modele {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var boolean
     *
     * @ORM\Column(name="bain", type="boolean")
     */
    private $bain;

    /**
     * @var boolean
     *
     * @ORM\Column(name="douche", type="boolean")
     */
    private $douche;

    /**
     * @var boolean
     *
     * @ORM\Column(name="wc", type="boolean")

     */
    private $wc;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrelit2", type="integer")
     * @Assert\NotBlank(message = "erreur.champs.vide")
     * @Assert\Min(limit = "0", message = "erreur.champs.negatif")    
     */
    private $nbrelit2;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbrelit1", type="integer")
     * @Assert\NotBlank(message = "erreur.champs.vide")
     * @Assert\Min(limit = "0", message = "erreur.champs.negatif")    

     */
    private $nbrelit1;

    /**
     * Construct
     */
    public function __construct() {
        $this
                ->setNbrelit1(0)
                ->setNbrelit2(0)
                ->setBain(false)
                ->setDouche(false)
                ->setWc(false);
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
     * Set bain
     *
     * @param boolean $bain
     * @return Modele
     */
    public function setBain($bain) {
        $this->bain = $bain;

        return $this;
    }

    /**
     * Get bain
     *
     * @return boolean 
     */
    public function getBain() {
        return $this->bain;
    }

    /**
     * Set douche
     *
     * @param boolean $douche
     * @return Modele
     */
    public function setDouche($douche) {
        $this->douche = $douche;

        return $this;
    }

    /**
     * Get douche
     *
     * @return boolean 
     */
    public function getDouche() {
        return $this->douche;
    }

    /**
     * Set wc
     *
     * @param boolean $wc
     * @return Modele
     */
    public function setWc($wc) {
        $this->wc = $wc;

        return $this;
    }

    /**
     * Get wc
     *
     * @return boolean 
     */
    public function getWc() {
        return $this->wc;
    }

    /**
     * Set nbrelit2
     *
     * @param integer $nbrelit2
     * @return Modele
     */
    public function setNbrelit2($nbrelit2) {
        $this->nbrelit2 = $nbrelit2;

        return $this;
    }

    /**
     * Get nbrelit2
     *
     * @return integer 
     */
    public function getNbrelit2() {
        return $this->nbrelit2;
    }

    /**
     * Set nbrelit1
     *
     * @param integer $nbrelit1
     * @return Modele
     */
    public function setNbrelit1($nbrelit1) {
        $this->nbrelit1 = $nbrelit1;

        return $this;
    }

    /**
     * Get nbrelit1
     *
     * @return integer 
     */
    public function getNbrelit1() {
        return $this->nbrelit1;
    }

}