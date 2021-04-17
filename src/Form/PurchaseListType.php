<?php

namespace App\Form;

use App\Entity\StockRequestList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('product')
        ->add('spcicification')
        ->add('unitOfMeasure')
        ->add('quantity')
        // ->add('status')
        
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockRequestList::class,
        ]);
    }
}
