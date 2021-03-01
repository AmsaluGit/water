<?php

namespace App\Form;

use App\Entity\Sells;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SellsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placeOfDelivery')
            ->add('paymentVoucherNumber')
            ->add('plateNumber')
            ->add('trailNumber')
            ->add('driver')
            ->add('phone')
            // ->add('note')
            ->add('receivedBy')
            // ->add('deliveredBy')
            // ->add('approvedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sells::class,
        ]);
    }
}
