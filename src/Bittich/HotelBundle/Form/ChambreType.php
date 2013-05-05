<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ChambreType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('etage', 'integer', array('label' => 'chambre.etage', 'attr' => array(
                        'class' => 'input-medium',
                        )))
                ->add('litbebe', 'checkbox', array('label' => 'chambre.litbebe', 'required' => false))
                ->add('modele', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Modele',
                    'property' => 'id',
                    'multiple' => false,
                    'required' => true,
                    'label' => "modele.label",
                    'attr' => array(
                        'class' => 'input-medium',
                    ),
                ))
                ->add('disponibilites', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Calendrier',
                    'property' => 'datestring',
                    'multiple' => true,
                    'required' => false,
                    'label' => "calendrier.disponibilites",
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Chambre'
        ));
    }

    public function getName() {
        return 'bittich_hotelbundle_chambretype';
    }

}
