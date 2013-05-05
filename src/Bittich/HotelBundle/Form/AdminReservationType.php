<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AdminReservationType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $date = new \DateTime();
        $ycurr = $date->format('Y'); //pour avoir l'année courante      
        $builder
                ->add('dateArrivee', 'date', array(
                    'label' => 'resa.date_arrivee',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-y',
                    'required' => true,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                ))
                ->add('dateDepart', 'date', array(
                    'label' => 'resa.date_depart',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-y',
                    'required' => true,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                        )
                )
                ->add('prixtotal', 'integer', array(
                    'label' => 'resa.prix_total',
                    'required' => false,
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                ))
                ->add('nbreBebe', 'integer', array(
                    'label' => 'resa.nbre_bebe',
                    'required' => false,
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                ))
                ->add('acompteDemande', 'integer', array(
                    'label' => 'resa.accompte',
                    'required' => false,
                    'attr' => array(
                        'class' => 'input-medium',
                    )
                ))
                ->add('dateLimiteacompte', 'date', array(
                    'label' => 'resa.date_limite',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-y',
                    'required' => false,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                          'attr' => array(
                        'class' => 'input-medium',
                    )
                        )
                )
                ->add('dateVersementacompte', 'date', array(
                    'label' => 'resa.date_versement',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-y',
                    'required' => false,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                          'attr' => array(
                        'class' => 'input-medium',
                    )
                        )
                )
                ->add('dateReservation', 'date', array(
                    'label' => 'resa_date_resa',
                    'widget' => 'single_text',
                    'format' => 'dd-MM-y',
                    'required' => true,
                    'read_only' => true,
                          'attr' => array(
                        'class' => 'input-medium',
                    ),
                    'years' => \range($ycurr, $ycurr + 1, 1),
                ))
                ->add('client', 'entity', array(
                    'class' => 'Bittich\UserBundle\Entity\User',
                    'property' => 'nom',
                    'multiple' => false,
                    'required' => true,
                    'disabled' => true,
                          'attr' => array(
                        'class' => 'input-medium',
                    ),
                    'label' => 'client.label',
                ))
                ->add('chambres', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Chambre',
                    'property' => 'numeroetage',
                    'multiple' => true,
                    'required' => true,
                    'label' => 'chambre.liste',
                          'attr' => array(
                        'class' => 'input-medium',
                    )
                ))


        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Reservation'
        ));
    }

    public function getName() {
        return 'bittich_hotelbundle_adminreservationtype';
    }

}
