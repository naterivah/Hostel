<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Bittich\HotelBundle\Entity\Modele;
use Bittich\HotelBundle\Form\ModeleType;

/**
 * Description of AccueilController
 *
 * @author nordine
 */
class ModeleController extends Controller {

    public function indexAction() {

        //return new Response('<html><body>Hello all!</body></html>');
    }

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
        $modeles = $em->getRepository('BittichHotelBundle:Modele')->findAll();
        return $this->render('BittichHotelBundle:Modele:lister.html.twig',
                array('modeles' => $modeles));
      
    }
    public function supprimerAction($id=null){
        $message='';
        if(isset($id)){
            $em= $this->getDoctrine()->getManager();
            $modele=$em->find('BittichHotelBundle:Modele', $id);
            if($modele){
                $em->remove($modele);
                $em->flush();
                $message.= $this->get('translator')->trans('modele.supprimer.succes',array('%name%'=> $id));
            }
        }else{
           $message.= $this->get('translator')->trans('modele.aucun');

        }
        //message flash
         $this->get('session')->getFlashBag()->add('message', $message);
       return $this->redirect($this->generateUrl('hotel_modele_lister'));
    }
    public function editerAction($id=null) {
        $typeAction = '';
        $message = '';
        $em = $this->getDoctrine()->getManager();
        if (isset($id)) {
            $modele = $em->
                    find('BittichHotelBundle:Modele', $id);
            if ($modele) {
                $typeAction = $this->get('translator')->trans('modele.modifier', array('%name%' => $id));
            } else {
                $message = $this->get('translator')->trans('modele.aucun');
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_modele_lister'));
            }
        } else {
            $modele = new Modele();
            $typeAction = $this->get('translator')->trans('modele.ajouter');
        }
        $form = $this->get('form.factory')->create(new ModeleType(), $modele);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {
                $em->persist($modele);
                $em->flush();

                if (isset($id)) {
                    $message = $this->get('translator')->trans('modele.modifier.succes', array('%name%' => $modele->getId()));
                } else {
                    $message = $this->get('translator')->trans('modele.ajouter.succes', array('%name%' => $modele->getId()));
                }
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_modele_lister'));
            

            }
        }
        //SI c'est pas POST
                return $this->render('BittichHotelBundle:Modele:editer-modele.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

}