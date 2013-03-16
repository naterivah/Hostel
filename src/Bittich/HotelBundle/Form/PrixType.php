<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PrixType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
               ->add('modele', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Modele',
                    'property' => 'id',
                    'multiple' => false,
                    'required' => true,
                    'label' => 'modele.label',
                ))
                ->add('tarif', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Tarif',
                    'property' => 'couleur',
                    'multiple' => false,
                    'required' => true,
                    'label' => 'tarif.label',
                ))
                ->add('prix','integer',array('label'=>'prix.label'))

        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Prix'
        ));
    }

    public function getName() {
        return 'bittich_hotelbundle_prixtype';
    }

}
