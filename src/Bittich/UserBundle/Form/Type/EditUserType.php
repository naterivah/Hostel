<?php

namespace Bittich\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use FOS\UserBundle\Form\Type\ProfileFormType as BaseType;

class EditUserType extends BaseType {

    public function buildUserForm(FormBuilderInterface $builder, array $options) {
        parent::buildUserForm($builder, $options);
        $builder
                ->add('nom', 'text', array('label' => 'client.nom',
                    'required' => true,))
                ->add('prenom', 'text', array('label' => 'client.prenom',
                    'required' => true,))
                ->add('adresse', 'text', array('label' => 'client.adresse',
                    'required' => true,))
                ->add('npostal', 'text', array('label' => 'client.npostal',
                    'required' => true,))
                ->add('localite', 'text', array('label' => 'client.localite',
                    'required' => true,))
        ;
    }

    public function getName() {
        return 'bittich_userbundle_editusertype';
    }

}