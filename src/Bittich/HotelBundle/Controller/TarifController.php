<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Bittich\HotelBundle\Entity\Tarif;
use Bittich\HotelBundle\Form\TarifType;

/**
 * Description of AccueilController
 *
 * @author nordine
 */
class TarifController extends Controller {

    public function indexAction() {

        //return new Response('<html><body>Hello all!</body></html>');
    }

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
        $tarifs = $em->getRepository('BittichHotelBundle:Tarif')->findAll();
        return $this->render('BittichHotelBundle:Tarif:lister.html.twig', array('tarifs' => $tarifs));
        //return new Response('<html><body>Hello all!</body></html>');
    }
    public function supprimerAction($id=null){
        $message='';
        if(isset($id)){
            $em= $this->getDoctrine()->getManager();
            $tarif=$em->find('BittichHotelBundle:Tarif', $id);
            if($tarif){
                $em->remove($tarif);
                $em->flush();
                $message.= $this->get('translator')->trans('tarif.supprimer.succes',array('%name%'=> $tarif->getCouleur()));
            }
        }else{
           $message.= $this->get('translator')->trans('tarif.aucun');

        }
        //message flash
         $this->get('session')->getFlashBag()->add('message', $message);
       return $this->redirect($this->generateUrl('hotel_tarif_lister'));
    }
    public function editerAction($id=null) {
        $typeAction = '';
        $message = '';
        $em = $this->getDoctrine()->getManager();
        if (isset($id)) {
            $tarif = $em->
                    find('BittichHotelBundle:Tarif', $id);
            if ($tarif) {
                $typeAction = $this->get('translator')->trans('tarif.modifier', array('%name%' => $tarif->getCouleur()));
            } else {
                $message = $this->get('translator')->trans('tarif.aucun');
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_tarif_lister'));
            }
        } else {
            $tarif = new Tarif();
            $typeAction = $this->get('translator')->trans('tarif.ajouter');
        }
        $form = $this->get('form.factory')->create(new TarifType(), $tarif);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {
                $em->persist($tarif);
                $em->flush();

                if (isset($id)) {
                    $message = $this->get('translator')->trans('tarif.modifier.succes', array('%name%' => $tarif->getCouleur()));
                } else {
                    $message = $this->get('translator')->trans('tarif.ajouter.succes', array('%name%' => $tarif->getCouleur()));
                }
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_tarif_lister'));
     

            }
        }
        //SI c'est pas POST
                return $this->render('BittichHotelBundle:Tarif:editer-tarif.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

}