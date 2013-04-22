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
                    'format' => 'd/M/y',
                    'required' => true,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                        )
                )
                ->add('dateDepart', 'date', array(
                    'label' => 'resa.date_depart',
                    'format' => 'd/M/y',
                    'required' => true,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                        )
                )
                ->add('prixtotal', 'integer', array(
                    'label' => 'resa.prix_total',
                    'required' => false,
                ))
                ->add('nbreBebe', 'integer', array(
                    'label' => 'resa.nbre_bebe',
                    'required' => false,
                ))
                ->add('acompteDemande', 'integer', array(
                    'label' => 'resa.accompte',
                    'required' => false,
                ))
                ->add('dateLimiteacompte', 'date', array(
                    'label' => 'resa.date_limite',
                    'format' => 'd/M/y',
                    'required' => false,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                        )
                )
                ->add('dateVersementacompte', 'date', array(
                    'label' => 'resa.date_versement',
                    'format' => 'd/M/y',
                    'required' => false,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                        )
                )
                ->add('dateReservation', 'date', array(
                    'label' => 'resa_date_resa',
                    'format' => 'd/M/y',
                    'required' => true,
                    'read_only' => true,
                    'years' => \range($ycurr, $ycurr + 1, 1),
                ))
                ->add('client', 'entity', array(
                    'class' => 'Bittich\UserBundle\Entity\User',
                    'property' => 'nom',
                    'multiple' => false,
                    'required' => true,
                    'disabled' => true,
                    'label' => 'client.label',
                ))
                ->add('chambres', 'entity', array(
                    'class' => 'Bittich\HotelBundle\Entity\Chambre',
                    'property' => 'numeroetage',
                    'multiple' => true,
                    'required' => true,
                    'label' => 'chambre.liste',
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
