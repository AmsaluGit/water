<?php

namespace App\Form;

use App\Entity\ProductDelivery;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductDeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('typeOfDocAndNum')
            // ->add('product')
            // ->add('specification')
            ->add('plateNumber')
            ->add('trialNumber')
            ->add('phoneNumber')
            // ->add('remark')
            ->add('handOveredBy', null, [
                'required'   => true
            ])
            // ->add('receivedBy')
            // ->add('deliveredBy')
            // ->add('approvedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductDelivery::class,
        ]);
    }
    
}
