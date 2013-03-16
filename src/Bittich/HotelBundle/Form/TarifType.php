<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('couleur','text',array('label'=>'tarif.couleur',
                    'required'=>true,
                
                ))
            ->add('prixlitbebe', 'integer', array('label'=>'tarif.bebe'))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Tarif'
        ));
    }

    public function getName()
    {
        return 'bittich_hotelbundle_tariftype';
    }
}
