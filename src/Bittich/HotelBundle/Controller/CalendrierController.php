<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Bittich\HotelBundle\Entity\Calendrier;
use Bittich\HotelBundle\Form\CalendrierType;
use \Bittich\HotelBundle\Repository\CalendrierRepository;
use Doctrine\DBAL\DBALException;
//comm
/**
 * Description of AccueilController
 *
 * @author nordine
 */
class CalendrierController extends Controller {
    /* retourne un tableau de dates représentant un intervalle entre deux dates */

    public function dateInterv($debut, $fin) {
        $difference = $debut->diff($fin);
        $diff = $difference->days;
        $disp = array();
        for ($i = 0; $i <= $diff; $i++) {
            $temp = clone $debut;
            $temp->add(new \DateInterval('P' . $i . 'D'));
            $disp[] = $temp;
        }
        return $disp;
    }

    public function recurs($litbebe, $idtar, $val) {
   
            $em = $this->getDoctrine()->getManager();
            $cal = new Calendrier();
            $tarif = $em->getRepository('BittichHotelBundle:Tarif')
                    ->findOneById($idtar);
            $cal->setDatej($val);
            $cal->setNbrelitbebe($litbebe);
            $cal->setTarif($tarif);
            $em->persist($cal);
            $em->flush();
            $em->clear();
            
        } 
    public function addRangeAction() {
        $req = $this->getRequest();
        $em = $this->getDoctrine()->getManager();

        if ($req->isXmlHttpRequest()) {
            $debut = new \DateTime($req->request->get('debut'));
            $fin = new \DateTime($req->request->get('fin'));
            $litbebe = $req->request->get('lits');
            $idtar = $req->request->get('tarif');
            $message = 'Les dates ont été ajoutée avec succès';
            $disp = $this->dateInterv($debut, $fin);
            foreach ($disp as $val) {
                try {

                    $resp = $this->recurs($litbebe, $idtar, $val);
          
                } catch (DBALException $e) {
                    $message = "Une erreur est survenue, vérifiez que les dates choisies ne sont pas déjà enregistrées.\n Voici le message: \n" + $e->getMessage();
                   return new JsonResponse(array("message" => $message));
                }

                // $em->clear();
            }
            return new JsonResponse(array("message" => $message));
        } else {
            $tarif = $em->getRepository('BittichHotelBundle:Tarif')
                    ->findAll();
            return $this->render('BittichHotelBundle:Calendrier:ajax-calendrier.html.twig', array('tarif' => $tarif));
        }
    }

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
        $cals =
                $em->getRepository('BittichHotelBundle:Calendrier')
                ->getCalendrierAvecTarif();

        return $this->render('BittichHotelBundle:Calendrier:lister.html.twig', array('calendriers' => $cals));
    }

    public function supprimerAction($id = null) {
        $message = '';
        if (isset($id)) {
            $em = $this->getDoctrine()->getManager();
            $cal = $em->find('BittichHotelBundle:Calendrier', $id);
            if ($cal) {
                $em->remove($cal);
                $em->flush();
                $message.= $this->get('translator')->trans('calendrier.supprimer.succes');
            }
        } else {
            $message.= $this->get('translator')->trans('calendrier.aucun');
        }
        //message flash
        $this->get('session')->getFlashBag()->add('message', $message);
        return $this->redirect($this->generateUrl('hotel_calendrier_lister'));
    }

    public function editerAction($id = null) {
        $typeAction = '';
        $message = '';
        $em = $this->getDoctrine()->getManager();
        if (isset($id)) {
            $cal = $em->
                    find('BittichHotelBundle:Calendrier', $id);
            if ($cal) {
                $typeAction = $this->get('translator')->trans('calendrier.modifier');
            } else {
                $message = $this->get('translator')->trans('calendrier.aucun');
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_calendrier_lister'));
            }
        } else {
            $cal = new Calendrier();
            $typeAction = $this->get('translator')->trans('calendrier.ajouter');
        }
        $form = $this->get('form.factory')->create(new CalendrierType(), $cal);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {
                $em->persist($cal);
                $em->flush();

                if (isset($id)) {
                    $message = $this->get('translator')->trans('calendrier.modifier.succes');
                } else {
                    $message = $this->get('translator')->trans('calendrier.ajouter.succes');
                }
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_calendrier_lister'));
            }
        }
        //SI c'est pas POST
        return $this->render('BittichHotelBundle:Calendrier:editer-calendrier.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

}