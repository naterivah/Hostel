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
    /* retourne un tableau de dates représentant un intervalle entre deux dates */

    public function dateInterv($arrivee, $depart) {
        $difference = $arrivee->diff($depart);
        $diff = $difference->days;
        $disp = array();
        for ($i = 0; $i < $diff; $i++) {
            $temp = clone $arrivee;
            $temp->add(new \DateInterval('P' . $i . 'D'));
            $disp[] = $temp;
        }
        return $disp;
    }

    /* Retourne un tableau de Calendrier */

    public function getCalendar($chambre, $interval, $litbebe) {
        $disp = array();
        $dispo = $chambre->getDisponibilites();
        //dispo est un Calendrier, du coup on doit vérifier que le nbre de lit bébé est correct
        foreach ($dispo as $val) {
            if (in_array($val->getDatej(), $interval) && $val->getNbrelitbebe() >= $litbebe) {
                $disp[] = $val;
            }
        }
        return $disp;
    }

    public function searchChbres() {
        //fonction utilitaire
        $req = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        $arrivee = new \DateTime($req->request->get('arrivee'));
        $depart = new \DateTime($req->request->get('depart'));
        $litbebe = $req->request->get('litbebe');
        $chambres = $em->getRepository("BittichHotelBundle:Chambre")->getChambre();
        $chbres = array();
        $nblit = 0;
        $interval = $this->dateInterv($arrivee, $depart);
        if (count($interval) == 0) {
            return array(); // si le gars réserve pour une journée, pour éviter les réservations à 0 euros.
        }
        foreach ($chambres as $chambre) {
            if ($chambre->getLitbebe() == true) {
                $nblit++;
            }
            $disp = $this->getCalendar($chambre, $interval, $litbebe); // on récupère le calendrier

            if (count($disp) == count($interval)) {

                $chbres[] = $chambre;
            }
        }
        if ($litbebe > $nblit) {
            $chbres = array();
        }
        return $chbres;
    }

    public function searchAction() {
        $req = $this->getRequest();
        if ($req->isXmlHttpRequest()) {
            $ch = $this->searchChbres();
            $chbres = array();
            foreach ($ch as $c) {
                $chbres[] = $c->getId();
            }
            if (count($chbres) > 0) {
                return new JsonResponse(array('chbres' => $chbres, 'status' => "ok"));
            } else {
                return new JsonResponse(array('status' => 'nok', 'message' => "Aucune chambre n'a été trouvée ou intervalle de dates trop court(min 1 nuit)"));
            }
        }
        return new JsonResponse("erreur fatal");
    }

    public function confirmAction() {
        $req = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        if ($req->isXmlHttpRequest()) {
            $prix = 0;
            $message = '';
            try {
                $resCh = $req->request->get('chambres'); // on récupère les id des chambres désirées
                if ($resCh == null || !isset($resCh) || count($resCh) == 0) {
                    return new JsonResponse(array('message' => "Vous n'avez séléctionné aucune chambre"));
                }

                $arrivee = new \DateTime($req->request->get('arrivee'));
                $depart = new \DateTime($req->request->get('depart'));
                $litbebe = $req->request->get('litbebe');
                $chbres = $this->searchChbres(); // on récupère la liste des chambres disponibles à ces dates
                //on teste si les chambres sont toujours disponibles
                $chambres = array();
                $stilldispo = true;
                $temp = array(); // pour les perfs...
                foreach ($chbres as $chambre) {
                    $temp[$chambre->getId()] = $chambre; // tableau associatif
                }
                foreach ($resCh as $rc) {
                    if (array_key_exists($rc, $temp)) {
                        $chambres[] = $temp[$rc];
                    } else {
                        $stilldispo = false; // la chambre n'est plus dispo...
                        break;
                    }
                }
                if ($stilldispo) {
                    $interv = $this->dateInterv($arrivee, $depart); // range de dates
                    /* création de la réservation */
                    // 1_ Recherche du nom d'utilisateur
                    $uscl = $this->container->get('security.context')->getToken()->getUser();
                    $usr = $em->getRepository('BittichUserBundle:User')->findUserByUserName($uscl);
                    // 2_ Création de l'objet Réservation
                    $res = new Reservation();
                    // 3_ Remplissage de l'objet
                    $res->setClient($usr);
                    $res->setDateArrivee($arrivee);
                    $res->setDateDepart($depart);
                    $res->setNbreBebe($litbebe);

                    // 3_a On met à jour les disponibilités et on  ajoute au prix le total avec les lits bébés compris
                    $dispo = $this->getCalendar($chambres[0], $interv, $litbebe);
                    foreach ($dispo as $val) {
                        $tar = $val->getTarif();
                        $prix+=$tar->getPrixlitbebe() * $litbebe; //Set prix bébé
                        $dj = $val->getNbrelitbebe();
                        $totbb = $dj - $litbebe;
                        $val->setNbrelitbebe($totbb);
                        $em->persist($val);
                    }

                    // 3_b On met à jour les chambres et on les persist
                    foreach ($chambres as $chambre) {
                        $idmodele = $chambre->getModele()->getId(); // on récupère le modèle de la chambre
                        // on calcule le total tarif par jour par chambre et on remove les dispos situées dans l'intervale
                        $dispo = $this->getCalendar($chambre, $interv, $litbebe);
                        foreach ($dispo as $d) {
                            $tar = $d->getTarif();
                            $pr = $em->getRepository("BittichHotelBundle:Prix")->findPrix($idmodele, $tar->getId()); //récupère le prix
                            $prix+=$pr->getPrix(); //set prix 
                            $chambre->removeDisponibilite($d);
                        }
                        // Persist la chambre avant de sortir de boucle
                        $res->addChambre($chambre);
                    }
                    $res->setPrixtotal($prix);
                    /* 4_ Persist et flush */
                    $em->persist($res);
                    $em->flush();
                    $message.= 'Ajout réussi!' . " Prix total à payer: " . $res->getPrixtotal() . "€, N° de réservation : " . $res->getId();
                }
            } catch (\Exception $e) {
                $message.= "Une erreur est survenue lors de la création de la réservation, en voici le message: " . $e->getMessage();
            }
            return new JsonResponse(array('message' => $message));
        }
        return new JsonResponse(array('message' => "Erreur Ligne 166 ReservationController"));
    }

    public function listerAction($page) {
        $em = $this->getDoctrine()->getManager();

        $reservations =
                $em->getRepository('BittichHotelBundle:Reservation')
                ->pagine(5, $page); //cinq aricles par page

        return $this->render('BittichHotelBundle:Reservation:lister.html.twig', array('reservations' => $reservations,
                    'page' => $page,
                    'all' => true,
                    'nombrePage' => ceil(count($reservations) / 5)));
    }

    public function listeReservationAction($page) {
        $em = $this->getDoctrine()->getManager();
        $uscl = $this->container->get('security.context')->getToken()->getUser();
        $usr = $em->getRepository('BittichUserBundle:User')->findUserByUserName($uscl);
        $reservations =
                $em->getRepository('BittichHotelBundle:Reservation')
                ->findByUser($usr->getId(),5, $page);

        return $this->render('BittichHotelBundle:Reservation:lister.html.twig', array('reservations' => $reservations,
                    'page' => $page,
                    "all" => false,
                    'nombrePage' => ceil(count($reservations) / 5)));
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

}
