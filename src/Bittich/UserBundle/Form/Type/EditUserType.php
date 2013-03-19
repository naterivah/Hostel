<?php

namespace Bittich\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class EditUserType extends BaseType {

    public function buildUserForm(FormBuilderInterface $builder, array $options) {
        parent::buildUserForm($builder, $options);
        $builder
                ->add('nom', 'text', array('label' => 'user.nom',
                    'required' => true,))
                ->add('prenom', 'text', array('label' => 'user.prenom',
                    'required' => true,))
                ->add('adresse', 'text', array('label' => 'user.adresse',
                    'required' => true,))
                ->add('npostal', 'text', array('label' => 'user.npostal',
                    'required' => true,))
                ->add('localite', 'text', array('label' => 'user.localite',
                    'required' => true,))
        ;
    }

    public function getName() {
        return 'bittich_userbundle_editusertype';
    }

}