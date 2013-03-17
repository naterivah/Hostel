<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class CalendrierType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->add('datej', 'date', array(
                    'label' => 'calendrier.datej',
                    'format' => 'd/M/y',
                     'required' => true,
                        )
                )
                //->add('nbredispo', 'integer', array('label' => 'calendrier.nbredispo'))
                ->add('tarif', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Tarif',
                    'property' => 'couleur',
                    'multiple' => false,
                     'required' => true,
                    'label' => 'tarif.couleur',
                ))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Calendrier'
        ));
    }

    public function getName() {
        return 'bittich_hotelbundle_calendriertype';
    }

}
