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

    /* DEPRECATED*

      public function nouveauAction() {
      $req = $this->getRequest();
      $em = $this->getDoctrine()->getManager();

      $message = '';
      if ($req->getMethod() == "POST") {



      //return new Response(var_dump($arr,$dp,$arrivee,$depart));
      try {
      $arr = $req->request->get('arrivee');
      $dp = $req->request->get('depart');
      $arrivee = new \DateTime($arr);
      $depart = new \DateTime($dp);
      $arrivee->format('Y-m-d');
      $depart->format('Y-m-d');
      $difference = $arrivee->diff($depart);
      $diff = $difference->days;
      $litbebe = $req->get('litbebe');
      $chambres = $em->getRepository("BittichHotelBundle:Chambre")->getChambre();

      $chbres = array();

      if ($difference->invert == 0 && $diff != 0) {
      $nblit = 0;
      foreach ($chambres as $chambre) {
      if ($chambre->getLitbebe() == true) {
      $nblit++;
      }
      // $temp = clone $arrivee;
      $disp = array();
      $dispo = $chambre->getDisponibilites();
      for ($i = 0; $i < $diff; $i++) {
      $temp = clone $arrivee;
      $temp->add(new \DateInterval('P' . $i . 'D'));
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
      }
      $this->get('session')->getFlashBag()->add('message', $message);

      return $this->redirect($this->generateUrl('hotel_accueil'));
      } */

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
    /* Deprecated
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
      //   $temp = clone $arrivee;
      $idmodele = $chambre->getModele()->getId(); //récupère le modèle
      $message.="modele>" . $idmodele;
      $dispo = $chambre->getDisponibilites();
      $disp = array();
      for ($i = 0; $i < $diff; $i++) {
      $temp = clone $arrivee;
      $temp->add(new \DateInterval('P' . $i . 'D'));
      // $temp->add(new \DateInterval('P1D'));
      $message.="j'entre";
      foreach ($dispo as $val) {
      if ($val->getDatej() == $temp) {
      // on fait un truc naze pour sauver la dispo
      $present = false;
      if (in_array($val, $disp)) {
      $present = true;
      }if (!$present) {
      $disp[] = $val;
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
      } */
}