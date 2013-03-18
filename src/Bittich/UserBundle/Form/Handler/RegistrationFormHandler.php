<?php
namespace Bittich\UserBundle\Form\Handler;
use FOS\UserBundle\Form\Handler\RegistrationFormHandler as BaseHandler;

# J'override RegistrationFormHandler pour ajouter le role_client
# à l'utilisateur à l'inscription

class RegistrationFormHandler extends BaseHandler
{
   

    /**
     * @param boolean $confirmation
     */
    public function process($confirmation = false)
    {
        $user = $this->createUser();
        $this->form->setData($user);
        $user->addRole('ROLE_CLIENT');
        if ('POST' === $this->request->getMethod()) {
            $this->form->bind($this->request);

            if ($this->form->isValid()) {
                $this->onSuccess($user, $confirmation);

                return true;
            }
        }

        return false;
    }

    
}
