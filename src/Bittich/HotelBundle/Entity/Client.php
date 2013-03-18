<?php

namespace Bittich\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Client
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\ClientRepository")
 */
class Client
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
    
    
   /** 
     * @ORM\OneToOne(targetEntity="Bittich\UserBundle\Entity\User", cascade={"persist","remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @Assert\Type(type="Bittich\UserBundle\Entity\User")
     */        
    private $user;  

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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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
     * @return Client
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

    /**
     * Set user
     *
     * @param \Bittich\UserBundle\Entity\User $user
     * @return Client
     */
    public function setUser(\Bittich\UserBundle\Entity\User $user = null)
    {
        $this->user = $user;
    
        return $this;
    }

    /**
     * Get user
     *
     * @return \Bittich\UserBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}