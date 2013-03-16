<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Bittich\HotelBundle\Entity\Prix;
use Bittich\HotelBundle\Form\PrixType;
use \Bittich\HotelBundle\Repository\PrixRepository;
//comm
/**
 * Description of AccueilController
 *
 * @author nordine
 */
class PrixController extends Controller {

    public function indexAction() {

        //return new Response('<html><body>Hello all!</body></html>');
    }

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
        $prices=
               $em->getRepository('BittichHotelBundle:Prix')
                ->getPrixComplet();
       
        return $this->render('BittichHotelBundle:Prix:lister.html.twig',
                array('prices' => $prices));
       
    }
    public function supprimerAction($idmodele=null,$idtarif=null){
        $message='';
        if(isset($idmodele) &&isset($idtarif)){
            $em= $this->getDoctrine()->getManager();
            $rep=$em->getRepository('BittichHotelBundle:Prix');
            $prix=$rep->findPrix($idmodele,$idtarif);
            if($prix){
                $em->remove($prix);
                $em->flush();
                $message.= $this->get('translator')->trans('prix.supprimer.succes');
            }
        }else{
           $message.= $this->get('translator')->trans('prix.aucun');

        }
        //message flash
         $this->get('session')->getFlashBag()->add('message', $message);
       return $this->redirect($this->generateUrl('hotel_prix_lister'));
    }
    public function editerAction($idmodele=null,$idtarif=null) {
        $typeAction = '';
        $message = '';
        $request = $this->getRequest();
        $em = $this->getDoctrine()->getManager();
        if (isset($idmodele)&& isset($idtarif)) {
            $rep=$em->getRepository('BittichHotelBundle:Prix');
            $prix=$rep->findPrix($idmodele,$idtarif);
            if ($prix) {
                $typeAction = $this->get('translator')->trans('prix.modifier');
            } else {
                $message = $this->get('translator')->trans('prix.aucun');
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_prix_lister'));
            }
        } else {
            $prix = new Prix();
            $typeAction = $this->get('translator')->trans('prix.ajouter');
        }
        $form = $this->get('form.factory')->create(new PrixType(),
                $prix);
        
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {
                $em->persist($prix);
                $em->flush();

                if (isset($idmodele)&& isset($idtarif)) {
                    $message = $this->get('translator')->trans('prix.modifier.succes');
                } else {
                    $message = $this->get('translator')->trans('prix.ajouter.succes');
                }
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_prix_lister'));
     

            }
        }
        //SI c'est pas POST
                return $this->render('BittichHotelBundle:Prix:editer-prix.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

}