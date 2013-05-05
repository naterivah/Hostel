<?php

namespace Bittich\UserBundle\Form\Type;

use Bittich\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;

class UserType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        parent::buildForm($builder, $options);
        $builder
                ->add('nom', 'text', array('label' => 'user.nom',
                    'required' => true, 'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('prenom', 'text', array('label' => 'user.prenom',
                    'required' => true, 'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('adresse', 'text', array('label' => 'user.adresse',
                    'required' => true, 'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('npostal', 'text', array('label' => 'user.npostal',
                    'required' => true, 'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('localite', 'text', array('label' => 'user.localite',
                    'required' => true, 'attr' => array(
                        'class' => 'input-medium',
                        )))
        ;
    }

    /* public function setDefaultOptions(OptionsResolverInterface $resolver) {
      $resolver->setDefaults(array(
      'data_class' => 'Bittich\UserBundle\Entity\User'
      ));
      } */

    public function getName() {
        return 'bittich_userbundle_usertype';
    }

}