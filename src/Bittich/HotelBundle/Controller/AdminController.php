<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bittich\HotelBundle\Entity\Client;
use Bittich\HotelBundle\Form\ClientType;
use FOS\UserBundle\Util\UserManipulator;
# GESTION DES CLIENTS, PROMOTION EN EMPLOYE
class AdminController extends Controller {

    public function listerAction($role) {
        $em = $this->getDoctrine()->getManager();
        //on recupere toutes les chambres et leur modele(sinon on risque de requêter dans la boucle )

        $clients =
                $em->getRepository('BittichUserBundle:User')
                ->findUserByRole($role);

        return $this->render('BittichHotelBundle:Client:lister.html.twig', array('clients' => $clients));
    }

    public function inscriptionAction() {
        $message = $this->get('translator')->trans('client.inscription');

        $em = $this->getDoctrine()->getManager();
        $client = new Client();
        $typeAction = $this->get('translator')->trans('client.inscription');

        $form = $this->get('form.factory')->create(new ClientType(), $client);
        $request = $this->getRequest();
        if ($request->isMethod("POST")) {
            $form->bind($request); // soummettre le formulaire
            if ($form->isValid()) {
                $em->persist($client);
                $em->flush();
                /*
                 * j'ajoute un role à mon client
                 */
                $user = $client->getUser();
                $userManager = $this->get('fos_user.user_manager');
                $userManipulator = new UserManipulator($userManager);
                $userManipulator->addRole($user->getUsername(), 'ROLE_CLIENT');
                #Activation du compte client
                $userManipulator->activate($user->getUsername());
                $message = $this->get('translator')->trans('client.ajouter.succes');

                //message flash
                $this->get('session')->getFlashBag()->add('message', $message);
                return $this->redirect($this->generateUrl('hotel_client_lister'));
            }
        }
        //SI c'est pas POST
        return $this->render('BittichHotelBundle:Client:inscription-client.html.twig', array(
                    'form' => $form->createView(),
                    'typeAction' => $typeAction,
                    'message' => $message
                        )
        );
    }

}
