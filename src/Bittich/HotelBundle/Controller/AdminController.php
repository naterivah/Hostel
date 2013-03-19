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
        $titre= 'user.liste';
        $users = $this->getDoctrine()
                ->getManager()
                ->getRepository('BittichUserBundle:User')
                ->findAll();
        $form = $this->get('form.factory')->create(new RechercheForm());

        return $this->render('BittichHotelBundle:Admin:lister.html.twig', array('users' => $users,
                    'form' => $form->createView(),
                    'titre' => $titre,
            
            ));
    }

    #Lister les utilisateurs en fonction de leur rôle

    public function listerRoleAction($role) {
        $em = $this->getDoctrine()->getManager();
        $titre='';
        switch($role){
            case 'employe': $role='ROLE_EMPLOYE';
                $titre.='employe.liste';
                break;
            case 'client': $role= 'ROLE_CLIENT';
                $titre='client.liste';
                
                break;
        }
        $users =
                $em->getRepository('BittichUserBundle:User')
                ->findUserByRole($role);
        $form = $this->get('form.factory')->create(new RechercheForm());

        return $this->render('BittichHotelBundle:Admin:lister.html.twig', array('users' => $users,
                    'form' => $form->createView(),
                    'titre' => $titre,
                    
                ))
        


        ;
    }

    #Promotion d'un utilisateur en employé

    public function promoteAction(User $user) {
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->addRole($user->getUsername(), 'ROLE_EMPLOYE');
        $userManipulator->removeRole($user->getUsername(), 'ROLE_CLIENT');

        $message = $this->get('translator')->trans('user.promote.succes',array('%name%'=>$user->getUsername()));
        $this->get('session')->getFlashBag()->add('message', $message);
        //ici on fait une redirection
        return $this->redirect($this->generateUrl('hotel_admin_lister'));
    }

    #Rétrogration d'un utilisateur en client

    public function demoteAction(User $user) {
        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->removeRole($user->getUsername(), 'ROLE_EMPLOYE');
        $userManipulator->addRole($user->getUsername(), 'ROLE_CLIENT');
        $message = $this->get('translator')->trans('user.demote.succes',array('%name%'=>$user->getUsername()));
        $this->get('session')->getFlashBag()->add('message', $message);
        //ici on fait une redirection
        return $this->redirect($this->generateUrl('hotel_admin_lister'));
    }

    #Désactivation d'un compte utilisateur 

    public function desactivateAction(User $user) {

        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->deactivate($user->getUserName());
        $message = $this->get('translator')->trans('user.inactif',array('%name%'=>$user->getUsername()));
        $this->get('session')->getFlashBag()->add('message', $message);

        return $this->redirect($this->generateUrl('hotel_admin_lister'));
    }

    #activation d'un compte

    public function reactivateAction(User $user) {

        $userManager = $this->get('fos_user.user_manager');
        $userManipulator = new UserManipulator($userManager);
        $userManipulator->activate($user->getUserName());
        $message = $this->get('translator')->trans('user.actif',array('%name%'=>$user->getUsername()));
        $this->get('session')->getFlashBag()->add('message', $message);

        return $this->redirect($this->generateUrl('hotel_admin_lister'));
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
                            , array('users' => $users,
                                'titre' =>'user.liste',
                                
                                ));
        } else {
            return $this->listerAction();
        }
    }

}
