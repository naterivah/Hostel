<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Bittich\HotelBundle\Entity\Calendrier;
use Bittich\HotelBundle\Form\CalendrierType;
use \Bittich\HotelBundle\Repository\CalendrierRepository;
//comm
/**
 * Description of AccueilController
 *
 * @author nordine
 */
class CalendrierController extends Controller {

    public function indexAction() {

        //return new Response('<html><body>Hello all!</body></html>');
    }

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
        $cals=
               $em->getRepository('BittichHotelBundle:Calendrier')
                ->getCalendrierAvecTarif();
       
        return $this->render('BittichHotelBundle:Calendrier:lister.html.twig',
                array('calendriers' => $cals));
       
    }
    public function supprimerAction($id=null){
        $message='';
        if(isset($id)){
            $em= $this->getDoctrine()->getManager();
            $cal=$em->find('BittichHotelBundle:Calendrier', $id);
            if($cal){
                $em->remove($cal);
                $em->flush();
                $message.= $this->get('translator')->trans('calendrier.supprimer.succes');
            }
        }else{
           $message.= $this->get('translator')->trans('calendrier.aucun');

        }
        //message flash
         $this->get('session')->getFlashBag()->add('message', $message);
       return $this->redirect($this->generateUrl('hotel_calendrier_lister'));
    }
    public function editerAction($id=null) {
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
        $form = $this->get('form.factory')->create(new CalendrierType(),
                $cal);
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