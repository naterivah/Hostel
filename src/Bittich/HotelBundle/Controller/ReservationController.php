<?php

namespace Bittich\HotelBundle\Controller;

use Bittich\HotelBundle\Entity\Reservation;
use Bittich\HotelBundle\Form\AdminReservationType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 *
 * @author nordine
 */
class ReservationController extends Controller {

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
        $reservations =
                $em->getRepository('BittichHotelBundle:Reservation')
                ->findAll();

        return $this->render('BittichHotelBundle:Reservation:lister.html.twig', array('reservations' => $reservations));
    }

    public function nouveauAction() {
        $req = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        $message = '';

        try {
            $arrivee = new \DateTime($req->get('arrivee'));
            $depart = new \DateTime($req->get('depart'));
            $difference = $arrivee->diff($depart);
            $diff = $difference->days;
            $litbebe = $req->get('litbebe');
            $chambres = $em->getRepository("BittichHotelBundle:Chambre")->getChambre();

            $chbres = array();

            if ($difference->invert == 0) {
                $nblit = 0;
                foreach ($chambres as $chambre) {
                    if ($chambre->getLitbebe() == true) {
                        $nblit++;
                    }
                    $temp = clone $arrivee;
                    $disp = array();
                    $dispo = $chambre->getDisponibilites();
                    for ($i = 0; $i < $diff; $i++) {
                        $temp->add(new \DateInterval('P1D'));
                        foreach ($dispo as $val) {
                            if ($val->getDatej() == $temp && $val->getNbrelitbebe() >= $litbebe) {
                                $disp[] = $val;
                                break;
                            }
                        }
                    }
                    if (count($disp) == $diff) {

                        $chbres[] = $chambre;
                    }
                }
                if ($litbebe > $nblit) {
                    $chbres = array();
                }
                if (count($chbres) > 0) {
                    //persist
                    $uscl = $this->container->get('security.context')->getToken()->getUser();
                    $res = new Reservation();
                    $usr = $em->getRepository('BittichUserBundle:User')->findUserByUserName($uscl);
                    $res->setClient($usr);
                    foreach ($chbres as $c) {
                        $res->addChambre($c);
                    }
                    $res->setDateArrivee($arrivee);
                    $res->setDateDepart($depart);
                    $res->setNbreBebe($litbebe);
                    $em->persist($res);
                    $em->flush();
                    // $id = $res->getId();
                    return $this->render('BittichHotelBundle:Reservation:nouvelle-reservation.html.twig', array(
                                'res' => $res,
                                    )
                    );
                } else {
                    $message.= $this->get('translator')->trans('chambre.aucun');
                }
            } else {
                $diff = -($diff);
                $message.= $this->get('translator')->trans('resa.datenegative');
                //ici indiquer une erreur date inférieure
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
        }

        $this->get('session')->getFlashBag()->add('message', $message);

        return $this->redirect($this->generateUrl('hotel_accueil'));
    }

    public function supprimerAction(Reservation $res) {
        $message = '';
        $em = $this->getDoctrine()->getManager();
        $em->remove($res);
        $em->flush();
        $message.= $this->get('translator')->trans('resa.supprimer.succes');
//message flash
        $this->get('session')->getFlashBag()->add('message', $message);
        return $this->redirect($this->generateUrl('hotel_reservation_lister'));
    }

    public function editerAction($id = null) {
        $typeAction = '';
        $message = '';
        $em = $this->getDoctrine()->getManager();
        if (isset($id)) {
            $res = $em->
                    find('BittichHotelBundle:Reservation', $id);
            if ($res) {
                $typeAction = $this->get('translator')->trans('resa.modifier');
            } else {
                $message = $this->get('translator')->trans('resa.aucun');
//message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_reservation_lister'));
            }
        } else {

            $res = new Reservation();
            $typeAction = $this->get('translator')->trans('resa.ajouter');
        }
        $form = $this->get('form.factory')->create(new AdminReservationType(), $res);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {

                $em->persist($res);
                $em->flush();


                if (isset($id)) {
                    $message = $this->get('translator')->trans('resa.modifier.succes');
                } else {
                    $message = $this->get('translator')->trans('resa.ajouter.succes');
                }
//message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_reservation_lister'));
            }
        }
//SI c'est pas POST
        return $this->render('BittichHotelBundle:Reservation:editer-reservation.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

// ***********************************************************************************************************************************************
//***********************************************************************************************************************************************

    public function nouvelleReservationAction(Reservation $res) {
        $req = $this->getRequest();
        $em = $this->getDoctrine()->getManager();


        if ($req->getMethod() == "POST") {
            $prix = 0;
            $message = '';
            // Récupération de la réservation, oui le code est pas terrible mais ça fait le boulot ;-)

            $arrivee = $res->getDateArrivee();

            $depart = $res->getDateDepart();
            $diff = $arrivee->diff($depart)->days;
            $message.="diff: " . $diff;
            // récupération des id chambres
            $idchambres = $req->request->get("chambres");
            $chambres = array();
            // on récupère nos chambres choisies par l'utilisateur
            foreach ($idchambres as $i) {
                $chambres[] = $em->getRepository("BittichHotelBundle:Chambre")->find($i);
                $message.="chambre trouvée" . $i;
            }
            // on remove les dispos des chambres et on incrémente le prix
       
            // on efface toutes les chambres, codage à la Microsoft Windows 98 Standard edition 2
            // c'est de loin la pire classe contrôleur que j'ai jamais écrite, j'en suis navré
            $res->clearChambres();
            $litbb = 0; // jm'ets ca pr calculer le prix des lits correctement

            foreach ($chambres as $chambre) {
                $temp = clone $arrivee;
                $idmodele = $chambre->getModele()->getId(); //récupère le modèle
                $message.="modele>" . $idmodele;
                $dispo = $chambre->getDisponibilites();
                $disp=array();
                for ($i = 0; $i < $diff; $i++) {
                    $temp->add(new \DateInterval('P1D'));
                    $message.="j'entre";
                    foreach ($dispo as $val) {
                        if ($val->getDatej() == $temp) {
                            // on fait un truc naze pour sauver la dispo
                            $present=false;
                          if (in_array($val, $disp)) {
                              $present=true;
                          }if(!$present){
                                $disp[]= $val;
                            }
                            // on modifie le nbre de litbébé
                            $message.="nombre de bébés =====>" . $res->getNbreBebe();
                            //$em->persist($val); // pas sur que ce soit obligatoire, methode ScrumVista
                            //prix lit bébé
                            $tar = $val->getTarif(); //récupère le tarif
                            $message.="tarif=>" . $tar->getId();
                            if ($litbb < $res->getNbreBebe() && $chambre->getLitbebe() == true) {
                                $prix+=$tar->getPrixlitbebe() * $res->getNbreBebe(); //Set prix bébé
                                $litbb+=1;
                            }
                            $pr = $em->getRepository("BittichHotelBundle:Prix")->findPrix($idmodele, $tar->getId()); //récupère le prix
                            $prix+=$pr->getPrix(); //set prix 
                           // $tmp = clone $chambre; // Super pour des performances incroyables! z
                            $chambre->removeDisponibilite($val);
                            $em->persist($chambre);
                            $message.="prix=>" . $prix;
                            break;
                        }
                    }
                }
                $res->addChambre($chambre);
            }

            

            $res->setPrixtotal($prix);
            $nbb = $res->getNbreBebe();
            foreach ($disp as $d) {
                $dj = $d->getNbrelitbebe();
                $totbb = $dj - $nbb;
                $d->setNbrelitbebe($totbb);
                $em->persist($d);
            }
            // enfin, on persist et flush
            $em->persist($res);
            $em->flush();
            //on renvoie la balle, jeu set et match
            $message .= 'La commande numéro ' . $res->getId() . " est prête à la boucherie. Prix total : " . $res->getPrixTotal() . "€";
            $this->get('session')->getFlashBag()->add('message', $message);

            return $this->redirect($this->generateUrl('hotel_accueil'));
        }
        $message = "erreur inconnue, quelque part à la ligne 215 du controller reservation(flemme de faire un if)";

        $this->get('session')->getFlashBag()->add('message', $message);
        return $this->redirect($this->generateUrl('hotel_accueil'));
    }

}