<?php

namespace Bittich\UserBundle\Entity;

use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="Bittich\UserBundle\Repository\UserRepository")
 * @ORM\Table(name="utilisateur")
 */
class User extends BaseUser {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255)
     * @Assert\NotBlank(message = "erreur.champs.vide")

     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255)
     * @Assert\NotBlank(message = "erreur.champs.vide")

     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="adresse", type="string", length=255)
     * @Assert\NotBlank(message = "erreur.champs.vide")

     */
    private $adresse;

    /**
     * @var string
     *
     * @ORM\Column(name="npostal", type="string", length=5)
     * @Assert\NotBlank(message = "erreur.champs.vide")

     */
    private $npostal;

    /**
     * @var string
     *
     * @ORM\Column(name="localite", type="string", length=255)
     * @Assert\NotBlank(message = "erreur.champs.vide")

     */
    private $localite;

    public function __construct() {
        parent::__construct();
      
        // your own logic
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
     * Set nom
     *
     * @param string $nom
     * @return User
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    
        return $this;
    }

    /**
     * Get nom
     *
     * @return string 
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * Set prenom
     *
     * @param string $prenom
     * @return User
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    
        return $this;
    }

    /**
     * Get prenom
     *
     * @return string 
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * Set adresse
     *
     * @param string $adresse
     * @return User
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    
        return $this;
    }

    /**
     * Get adresse
     *
     * @return string 
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * Set npostal
     *
     * @param string $npostal
     * @return User
     */
    public function setNpostal($npostal)
    {
        $this->npostal = $npostal;
    
        return $this;
    }

    /**
     * Get npostal
     *
     * @return string 
     */
    public function getNpostal()
    {
        return $this->npostal;
    }

    /**
     * Set localite
     *
     * @param string $localite
     * @return User
     */
    public function setLocalite($localite)
    {
        $this->localite = $localite;
    
        return $this;
    }

    /**
     * Get localite
     *
     * @return string 
     */
    public function getLocalite()
    {
        return $this->localite;
    }
}
