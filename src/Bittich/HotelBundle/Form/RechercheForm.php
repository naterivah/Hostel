<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RechercheForm extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('motcle','text', array(
                   
                     'attr'   =>  array(
                        'class'   => 'input-medium search-query',
                        'placeholder' => 'search')
                    
                    
                    ));
    }


    #On utilise le nom du formulaire dans le script ajax!
    public function getName() {
        return 'rechercheform';
    }

}
