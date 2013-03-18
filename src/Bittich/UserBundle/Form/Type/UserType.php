<?php

namespace Bittich\UserBundle\Form\Type;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use Symfony\Component\Form\FormBuilderInterface;
class UserType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
       
            parent::buildForm($builder, $options);
 $builder
            ->add('nom','text',
                    array('label'=>'client.nom',
                    'required'=>true,))
            ->add('prenom','text',
                    array('label'=>'client.prenom',
                    'required'=>true,))
            ->add('adresse','text',
                    array('label'=>'client.adresse',
                    'required'=>true,))
            ->add('npostal','text',
                    array('label'=>'client.npostal',
                    'required'=>true,))
            ->add('localite','text',
                    array('label'=>'client.localite',
                    'required'=>true,))
            ;
        
    }

    /*public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\UserBundle\Entity\User'
        ));
    }*/

    public function getName() {
        return 'bittich_userbundle_usertype';
    }

}