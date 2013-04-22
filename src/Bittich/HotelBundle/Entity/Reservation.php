<?php

namespace Bittich\HotelBundle\Entity;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Bittich\UserBundle\Entity\User;
use Symfony\Component\Validator\ExecutionContext;
use Symfony\Component\Finder\Comparator\DateComparator;

/**
 * Reservation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Bittich\HotelBundle\Repository\ReservationRepository")
 * @Assert\Callback(methods={"testDateValidite"})

 */
class Reservation {

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Bittich\UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message = "erreur.champs.vide")
     */
    private $client;

    /**
     * @ORM\ManyToMany(targetEntity="Chambre", cascade = "persist")         
     * @ORM\JoinTable(name="pour")
     */
    private $chambres;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_arrivee", type="datetime")
     */
    private $dateArrivee;

    /**
     * @var \DateTime
     * @ORM\Column(name="date_depart", type="datetime")
     */
    private $dateDepart;

    /**
     * @var integer
     *
     * @ORM\Column(name="prixtotal", type="integer", nullable = true)
     */
    private $prixtotal;

    /**
     * @var integer
     *
     * @ORM\Column(name="nbre_bebe", type="integer", nullable = true)
     */
    private $nbreBebe;

    /**
     * @var integer
     *
     * @ORM\Column(name="acompte_demande", type="integer", nullable = true)
     */
    private $acompteDemande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_limiteacompte", type="datetime", nullable = true)
     */
    private $dateLimiteacompte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_versementacompte", type="datetime", nullable = true)
     */
    private $dateVersementacompte;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_reservation", type="datetime", nullable = true)
     */
    private $dateReservation;

    public function __construct() {
        $now = new \DateTime('now');
        $this->setAcompteDemande(0)
                ->setNbreBebe(0)
                ->setPrixtotal(0)
                ->setDateReservation($now)
                ->setDateArrivee($now)
                ->setDateDepart($now)
                ->setDateLimiteacompte($now)
                ->setDateVersementacompte($now);
        
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
     * Set dateArrivee
     *
     * @param \DateTime $dateArrivee
     * @return Reservation
     */
    public function setDateArrivee($dateArrivee) {
        $this->dateArrivee = $dateArrivee;

        return $this;
    }

    /**
     * Get dateArrivee
     *
     * @return \DateTime 
     */
    public function getDateArrivee() {
        return $this->dateArrivee;
    }

    /**
     * Set dateDepart
     *
     * @param \DateTime $dateDepart
     * @return Reservation
     */
    public function setDateDepart($dateDepart) {
        $this->dateDepart = $dateDepart;

        return $this;
    }

    /**
     * Get dateDepart
     *
     * @return \DateTime 
     */
    public function getDateDepart() {
        return $this->dateDepart;
    }

    /**
     * Set prixtotal
     *
     * @param integer $prixtotal
     * @return Reservation
     */
    public function setPrixtotal($prixtotal) {
        $this->prixtotal = $prixtotal;

        return $this;
    }

    /**
     * Get prixtotal
     *
     * @return integer 
     */
    public function getPrixtotal() {
        return $this->prixtotal;
    }

    /**
     * Set nbreBebe
     *
     * @param integer $nbreBebe
     * @return Reservation
     */
    public function setNbreBebe($nbreBebe) {
        $this->nbreBebe = $nbreBebe;

        return $this;
    }

    /**
     * Get nbreBebe
     *
     * @return integer 
     */
    public function getNbreBebe() {
        return $this->nbreBebe;
    }

    /**
     * Set acompteDemande
     *
     * @param integer $acompteDemande
     * @return Reservation
     */
    public function setAcompteDemande($acompteDemande) {
        $this->acompteDemande = $acompteDemande;

        return $this;
    }

    /**
     * Get acompteDemande
     *
     * @return integer 
     */
    public function getAcompteDemande() {
        return $this->acompteDemande;
    }

    /**
     * Set dateLimiteacompte
     *
     * @param \DateTime $dateLimiteacompte
     * @return Reservation
     */
    public function setDateLimiteacompte($dateLimiteacompte) {
        $this->dateLimiteacompte = $dateLimiteacompte;

        return $this;
    }

    /**
     * Get dateLimiteacompte
     *
     * @return \DateTime 
     */
    public function getDateLimiteacompte() {
        return $this->dateLimiteacompte;
    }

    /**
     * Set dateVersementacompte
     *
     * @param \DateTime $dateVersementacompte
     * @return Reservation
     */
    public function setDateVersementacompte($dateVersementacompte) {
        $this->dateVersementacompte = $dateVersementacompte;

        return $this;
    }

    /**
     * Get dateVersementacompte
     *
     * @return \DateTime 
     */
    public function getDateVersementacompte() {
        return $this->dateVersementacompte;
    }

    /**
     * Set dateReservation
     *
     * @param \DateTime $dateReservation
     * @return Reservation
     */
    public function setDateReservation($dateReservation) {
        $this->dateReservation = $dateReservation;

        return $this;
    }

    /**
     * Get dateReservation
     *
     * @return \DateTime 
     */
    public function getDateReservation() {
        return $this->dateReservation;
    }

    # on teste la validité de la date à la soumission du formulaire (callback)

    public function testDateValidite(ExecutionContext $context) {

        $dateComparator = new DateComparator($this->dateDepart->format('Y-m-d H:i:s'));

        $dateComparator->setOperator(">");

        if ($dateComparator->test($this->dateArrivee->format('U'))) {

            $propertyPath = $context->getPropertyPath() . '.dateArrivee';
            $context->addViolationAtPath($propertyPath, 'erreur.date', array(), null);
        }
    }

    /**
     * Set client
     *
     * @param \Bittich\UserBundle\Entity\User $client
     * @return Reservation
     */
    public function setClient(\Bittich\UserBundle\Entity\User $client) {
        $this->client = $client;

        return $this;
    }

    /**
     * Get client
     *
     * @return \Bittich\UserBundle\Entity\User 
     */
    public function getClient() {
        return $this->client;
    }

    /**
     * Add chambres
     *
     * @param \Bittich\HotelBundle\Entity\Chambre $chambres
     * @return Reservation
     */
    public function addChambre(\Bittich\HotelBundle\Entity\Chambre $chambres) {
        $this->chambres[] = $chambres;

        return $this;
    }

    /**
     * Remove chambres
     *
     * @param \Bittich\HotelBundle\Entity\Chambre $chambres
     */
    public function removeChambre(\Bittich\HotelBundle\Entity\Chambre $chambres) {
        $this->chambres->removeElement($chambres);
    }

    /**
     * Get chambres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChambres() {
        return $this->chambres;
    }

}