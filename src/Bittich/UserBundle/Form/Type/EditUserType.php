<?php

namespace Bittich\UserBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Bittich\UserBundle\Form\Type\ProfileFormType as BaseType;

class EditUserType extends BaseType {

    public function buildUserForm(FormBuilderInterface $builder, array $options) {
        parent::buildUserForm($builder, $options);
        $builder
                ->add('nom', 'text', array('label' => 'user.nom',
                    'required' => true,'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('prenom', 'text', array('label' => 'user.prenom',
                    'required' => true,'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('adresse', 'text', array('label' => 'user.adresse',
                    'required' => true,'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('npostal', 'text', array('label' => 'user.npostal',
                    'required' => true,'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('localite', 'text', array('label' => 'user.localite',
                    'required' => true,'attr' => array(
                        'class' => 'input-medium',
                        )))
        ;
    }

    public function getName() {
        return 'bittich_userbundle_editusertype';
    }

}