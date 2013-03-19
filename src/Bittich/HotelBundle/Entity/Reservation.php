<?php

namespace Bittich\HotelBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\ReservationRepository")
 */
class Reservation
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_arrivee", type="datetime")
     */
    private $dateArrivee;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_depart", type="datetime")
     */
    private $dateDepart;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixtotal", type="integer")
     */
    private $prixtotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbre_bebe", type="integer")
     */
    private $nbreBebe;

    /**
     * @var integer
     *
     * @ORM\Column(name="acompte_demande", type="integer")
     */
    private $acompteDemande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_limiteacompte", type="datetime")
     */
    private $dateLimiteacompte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_versementacompte", type="datetime")
     */
    private $dateVersementacompte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="datetime")
     */
    private $dateReservation;


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
     * Set dateArrivee
     *
     * @param \DateTime $dateArrivee
     * @return Reservation
     */
    public function setDateArrivee($dateArrivee)
    {
        $this->dateArrivee = $dateArrivee;
    
        return $this;
    }

    /**
     * Get dateArrivee
     *
     * @return \DateTime 
     */
    public function getDateArrivee()
    {
        return $this->dateArrivee;
    }

    /**
     * Set dateDepart
     *
     * @param \DateTime $dateDepart
     * @return Reservation
     */
    public function setDateDepart($dateDepart)
    {
        $this->dateDepart = $dateDepart;
    
        return $this;
    }

    /**
     * Get dateDepart
     *
     * @return \DateTime 
     */
    public function getDateDepart()
    {
        return $this->dateDepart;
    }

    /**
     * Set prixtotal
     *
     * @param integer $prixtotal
     * @return Reservation
     */
    public function setPrixtotal($prixtotal)
    {
        $this->prixtotal = $prixtotal;
    
        return $this;
    }

    /**
     * Get prixtotal
     *
     * @return integer 
     */
    public function getPrixtotal()
    {
        return $this->prixtotal;
    }

    /**
     * Set nbreBebe
     *
     * @param integer $nbreBebe
     * @return Reservation
     */
    public function setNbreBebe($nbreBebe)
    {
        $this->nbreBebe = $nbreBebe;
    
        return $this;
    }

    /**
     * Get nbreBebe
     *
     * @return integer 
     */
    public function getNbreBebe()
    {
        return $this->nbreBebe;
    }

    /**
     * Set acompteDemande
     *
     * @param integer $acompteDemande
     * @return Reservation
     */
    public function setAcompteDemande($acompteDemande)
    {
        $this->acompteDemande = $acompteDemande;
    
        return $this;
    }

    /**
     * Get acompteDemande
     *
     * @return integer 
     */
    public function getAcompteDemande()
    {
        return $this->acompteDemande;
    }

    /**
     * Set dateLimiteacompte
     *
     * @param \DateTime $dateLimiteacompte
     * @return Reservation
     */
    public function setDateLimiteacompte($dateLimiteacompte)
    {
        $this->dateLimiteacompte = $dateLimiteacompte;
    
        return $this;
    }

    /**
     * Get dateLimiteacompte
     *
     * @return \DateTime 
     */
    public function getDateLimiteacompte()
    {
        return $this->dateLimiteacompte;
    }

    /**
     * Set dateVersementacompte
     *
     * @param \DateTime $dateVersementacompte
     * @return Reservation
     */
    public function setDateVersementacompte($dateVersementacompte)
    {
        $this->dateVersementacompte = $dateVersementacompte;
    
        return $this;
    }

    /**
     * Get dateVersementacompte
     *
     * @return \DateTime 
     */
    public function getDateVersementacompte()
    {
        return $this->dateVersementacompte;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return Reservation
     */
    public function setDateReservation($dateReservation)
    {
        $this->dateReservation = $dateReservation;
    
        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime 
     */
    public function getDateReservation()
    {
        return $this->dateReservation;
    }
}
