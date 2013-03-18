<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Bittich\UserBundle\Entity\User;
use FOS\UserBundle\Util\UserManipulator;
use Bittich\HotelBundle\Form\RechercheForm;

/**
 * GESTION DES CLIENTS, PROMOTION DES EMPLOYES
 */
class AdminController extends Controller {
    public function indexAction() {
        return $this->render('BittichHotelBundle:Admin:index.html.twig');
    }    


#Lister l'ensemble des utilisateurs

    public function listerAction() {
        $users = $this->getDoctrine()
                ->getManager()
                ->getRepository('BittichUserBundle:User')
                ->findAll();
        $form = $this->get('form.factory')->create(new RechercheForm());

        return $this->render('BittichHotelBundle:Admin:lister.html.twig', array('users' => $users,
                    'form' =>  $form->createView()));
    }

    #Lister les utilisateurs en fonction de leur rôle

    public function listerRoleAction($role) {
        $em = $this->getDoctrine()->getManager();

        $users =
                $em->getRepository('BittichUserBundle:User')
                ->findUserByRole($role);

        return $this->render('BittichHotelBundle:Admin:liste_base.html.twig', array('users' => $users));
    }

    #Promotion d'un utilisateur en employé

    public function promoteAction(User $user) {
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->addRole($user->getUsername(), 'ROLE_EMPLOYE');
        $userManipulator->removeRole($user->getUsername(), 'ROLE_CLIENT');

        $message = $this->get('translator')->trans('user.promote');
        $this->get('session')->getFlashBag()->add('message', $message);
        //ici on fait une redirection
        return $this->redirect($this->generateUrl('hotel_admin_accueil'));
    }

    #Désactivation d'un compte utilisateur 

    public function desactivateAction(User $user) {

        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->deactivate($user->getUserName());
        $message = $this->get('translator') ->trans('user.deactivate');
        $this->get('session')->getFlashBag()->add('message', $message);

        return $this->redirect($this->generateUrl('hotel_admin_accueil'));
    }
    
        #activation d'un compte
    
        public function reactivateAction(User $user) {
        
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->activate($user->getUserName());
        $message = $this->get('translator') -> trans('user.activate');
        $this->get('session')->getFlashBag()->add('message', $message);

        return $this->redirect($this->generateUrl('hotel_admin_accueil'));
    }

    #Fonction AJAX, recherche les utilisateurs en fonction du nom ou du prénom

    public function rechercheNomAction() {
        $req = $this->getRequest();

        if ($req->isXmlHttpRequest()) {
            $motcle = '';
            $motcle .= $req->request->get('motcle');
            if ($motcle != '') {
                $users = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('BittichUserBundle:User')
                        ->findUserByNomPrenom($motcle);
            } else {
                $users = $this
                        ->getDoctrine()            
                        ->getManager()
                        ->getRepository('BittichUserBundle:User')->findAll();
            }
            return $this->render("BittichHotelBundle:Admin:liste_base.html.twig"
                            , array('users' => $users));
        } else {
            return $this->listerAction();
        }
    }

}