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
        $message = '';
        // if ($req->isXmlHttpRequest()) {
        try {
            $arrivee = new \DateTime($req->get('arrivee'));
            $depart = new \DateTime($req->get('depart'));
            $difference = $arrivee->diff($depart);
            $diff = $difference->days;
            $litbebe = $req->get('litbebe');
            $em = $this->getDoctrine()->getManager();
            $chambres = $em->getRepository("BittichHotelBundle:Chambre")->getChambre();

            $chbres = array();

            if ($difference->invert == 0) {
                foreach ($chambres as $chambre) {

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

        // }
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
                //BEGIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIN
                $chambres = $res->getChambres();
                $arrivee = $res->getDateArrivee();
                $depart = $res->getDateDepart();

                $diff = $arrivee->diff($depart)->days;
                foreach ($chambres as $chambre) {

                    $temp = clone $arrivee;
                    $disp = array();
                    $dispo = $chambre->getDisponibilites();
                    for ($i = 0; $i < $diff; $i++) {
                        $temp->add(new \DateInterval('P1D'));
                        foreach ($dispo as $val) {
                            if ($val->getDatej() == $temp) {
                                $chambre->removeDisponibilite($val);
                                $em->persist($chambre);
                                break;
                            }
                        }
                    }
                }


                // FIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIIN
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

    public function nouvelleReservationAction() {
        $req = $this->getRequest();
        $ch = $req->get('id');
        $response = $this->forward('BittichHotelBundle:Reservation:editer', array(
            'id' => $ch,
                ));



        return $response;
    }

}