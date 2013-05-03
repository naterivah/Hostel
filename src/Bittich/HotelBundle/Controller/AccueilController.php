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


}