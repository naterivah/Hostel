<?php

namespace Bittich\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class BittichUserBundle extends Bundle {

    public function getParent() {
        return 'FOSUserBundle';
    }

}
