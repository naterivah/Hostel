<?php

namespace Bittich\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Tarif
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\TarifRepository")
 */
class Tarif
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="couleur", type="string", length=32)
     * @Assert\NotBlank(message = "erreur.champs.vide")
     * @Assert\Length(min = "3", minMessage="erreur.champs.minlength") 
     */
    private $couleur;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixlitbebe", type="integer")
     * @Assert\NotBlank(message = "erreur.champs.vide")
        @Assert\Min(limit = "0", message = "erreur.champs.negatif")    
     */
    private $prixlitbebe;

    /**
     * Construct 
     */
    public function __construct(){
        $this->prixlitbebe=0;
        
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
     * Set couleur
     *
     * @param string $couleur
     * @return Tarif
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
    
        return $this;
    }

    /**
     * Get couleur
     *
     * @return string 
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * Set prixlitbebe
     *
     * @param integer $prixlitbebe
     * @return Tarif
     */
    public function setPrixlitbebe($prixlitbebe)
    {
        $this->prixlitbebe = $prixlitbebe;
    
        return $this;
    }

    /**
     * Get prixlitbebe
     *
     * @return integer 
     */
    public function getPrixlitbebe()
    {
        return $this->prixlitbebe;
    }
}