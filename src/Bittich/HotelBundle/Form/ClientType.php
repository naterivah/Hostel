<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Bittich\HotelBundle\Form\UserType;
class ClientType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            #ici on fait appel au formulaire fos_userbundle pour créer l'utilisateur
            ->add('user',new UserType(),
                    array('label'=>'user',
                        'required'=>true))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Client'
        ));
    }

    public function getName()
    {
        return 'bittich_hotelbundle_clienttype';
    }
}
