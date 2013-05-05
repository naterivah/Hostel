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
                    'widget' => 'single_text',
                    'format' => 'dd-MM-y',
                    'required' => true,
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                        )
                )
                ->add('nbrelitbebe', 'integer', array(
                    'label' => 'calendrier.nbrelitbebe',
                    'required' => true,
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                        )
                )
                //->add('nbredispo', 'integer', array('label' => 'calendrier.nbredispo'))
                ->add('tarif', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Tarif',
                    'property' => 'couleur',
                    'multiple' => false,
                    'required' => true,
                    'label' => 'tarif.couleur',
                    'attr' => array(
                        'class' => 'input-medium',
                    )
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
