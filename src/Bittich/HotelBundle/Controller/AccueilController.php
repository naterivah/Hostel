<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Description of AccueilController
 *
 * @author nordine
 */
class AccueilController extends Controller {

    public function indexAction() {
        return $this->render('::layout.html.twig');
    }
    public function commenterAction(){
        return $this->render("BittichHotelBundle:Accueil:commenter.html.twig");
    }
    #Envoi d'un mail#
    public function bugAction(){
    }
    

}