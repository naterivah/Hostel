<?php

namespace Bittich\HotelBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

/**
 * Description of AccueilController
 *
 * @author nordine
 */
class AccueilController extends Controller {

    public function indexAction() {
      return $this->render('BittichHotelBundle:Default:index.html.twig');

    }

}