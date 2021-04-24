<?php

namespace App\Form;

use App\Entity\StockRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PurchaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        //    ->add('requestedBy')
           ->add('requestingDept',null, [
            'required'=>true,
            ])
        // ->add('date_of_request')
           ->add('section',null, [
            'required'=>true,
            ])
        //    ->add('approvedBy');
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockRequest::class,
        ]);
    }
}
