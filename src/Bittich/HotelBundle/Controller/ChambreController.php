<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Bittich\HotelBundle\Entity\Chambre;
use Bittich\HotelBundle\Entity\Calendrier;
use Bittich\HotelBundle\Form\ChambreType;
use \Bittich\HotelBundle\Repository\ChambreRepository;
/**
 * Description of AccueilController
 *
 * @author nordine
 */
class ChambreController extends Controller {

    public function indexAction() {

        //return new Response('<html><body>Hello all!</body></html>');
    }

    public function listerAction() {
        $em = $this->getDoctrine()->getManager();
    //on recupere toutes les chambres et leur modele(sinon on risque de requêter dans la boucle )
       
        $chambres=
               $em->getRepository('BittichHotelBundle:Chambre')
                ->getChambreAvecModele() ;
       
        return $this->render('BittichHotelBundle:Chambre:lister.html.twig',
                array('chambres' => $chambres));
       
    }
    public function supprimerAction($id=null){
        $message='';
        $chambre=new Chambre;
   
        if(isset($id)){
            $em= $this->getDoctrine()->getManager();
            $chambre=$em->find('BittichHotelBundle:Chambre', $id);
            
            if($chambre){

                $em->remove($chambre);
                $em->flush();
                $message.= $this->get('translator')->trans('chambre.supprimer.succes',
                        array('%name%'=> $chambre->getId()));
            }
        }else{
           $message.= $this->get('translator')->trans('chambre.aucun');

        }
        //message flash
         $this->get('session')->getFlashBag()->add('message', $message);
       return $this->redirect($this->generateUrl('hotel_chambre_lister'));
    }
    public function editerAction($id=null) {
        $typeAction = '';
        $message = '';
        $em = $this->getDoctrine()->getManager();
        if (isset($id)) {
            $chambre = $em->
                    find('BittichHotelBundle:Chambre', $id);
            if ($chambre) {
                $typeAction = $this->get('translator')->trans('chambre.modifier',
                        array('%name%' => $chambre->getId()));
            } else {
                $message = $this->get('translator')->trans('chambre.aucun');
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_chambre_lister'));
            }
        } else {
            $chambre = new Chambre();
            $typeAction = $this->get('translator')->trans('chambre.ajouter');
        }
        $form = $this->get('form.factory')->create(new ChambreType(), $chambre);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {
                $em->persist($chambre);
                $em->flush();

                if (isset($id)) {
                    $message = $this->get('translator')->trans('chambre.modifier.succes', array('%name%' => $chambre->getId()));
                } else {
                    $message = $this->get('translator')->trans('chambre.ajouter.succes', array('%name%' => $chambre->getId()));
                }
                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_chambre_lister'));
     

            }
        }
        //SI c'est pas POST
                return $this->render('BittichHotelBundle:Chambre:editer-chambre.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

}