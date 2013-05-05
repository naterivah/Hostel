<?php

namespace Bittich\HotelBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ModeleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('bain', 'checkbox',array('label'=>'modele.bain','required'=>false,'value'=>0))
            ->add('douche', 'checkbox',array('label'=>'modele.douche','required'=>false,'value'=>0))
            ->add('wc', 'checkbox',array('label'=>'modele.wc','required'=>false,'value'=>0))
            ->add('nbrelit2', 'integer', array('label'=>'modele.nbrelit2',  'attr'   =>  array(
                        'class'   => 'input-medium',),))
            ->add('nbrelit1', 'integer', array('label'=>'modele.nbrelit1',
                  'attr'   =>  array(
                        'class'   => 'input-medium',
                        )
                
                ))
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bittich\HotelBundle\Entity\Modele'
        ));
    }

    public function getName()
    {
        return 'bittich_hotelbundle_modeletype';
    }
}
