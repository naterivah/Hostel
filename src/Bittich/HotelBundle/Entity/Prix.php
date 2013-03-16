<?php

namespace Bittich\HotelBundle\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * Prix
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\PrixRepository")
 * @UniqueEntity(fields={"modele", "tarif"}, message="prix.unique")
 */
class Prix
{
  /**
   * @ORM\Id
   * @ORM\ManyToOne(targetEntity="Bittich\HotelBundle\Entity\Modele")
   * @ORM\JoinColumn(nullable=false)
   * @Assert\NotBlank(message = "erreur.champs.vide")
   */
  private $modele;
 
  /**
   * @ORM\Id
   * @ORM\ManyToOne(targetEntity="Bittich\HotelBundle\Entity\Tarif")
   * @ORM\JoinColumn(nullable=false)
   * @Assert\NotBlank(message = "erreur.champs.vide")
   */
  private $tarif;

    /**
     * @var integer
     *
     * @ORM\Column(name="prix", type="integer")
     * @Assert\NotBlank(message = "erreur.champs.vide")
     * @Assert\Min(limit = "0", message = "erreur.champs.negatif")    
     */
    private $prix;


   public function __construct(){
       $this->setPrix(0);
   }

    /**
     * Set prix
     *
     * @param integer $prix
     * @return Prix
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;
    
        return $this;
    }

    /**
     * Get prix
     *
     * @return integer 
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set modele
     *
     * @param \Bittich\HotelBundle\Entity\Modele $modele
     * @return Prix
     */
    public function setModele(\Bittich\HotelBundle\Entity\Modele $modele)
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

    /**
     * Set tarif
     *
     * @param \Bittich\HotelBundle\Entity\Tarif $tarif
     * @return Prix
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