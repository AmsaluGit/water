<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use App\Entity\ConsumptionRequest;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BalanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder

            ->add('quantity')
            ->add('remark')
            
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumptionRequest::class,
        ]);
    }
}
