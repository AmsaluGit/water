<?php

namespace App\Form;

use App\Entity\ConsumptionDeliveryList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumptionDeliveryListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('codeNumber')
            ->add('quantity')
            ->add('unitPrice')
            // ->add('remark')
            ->add('product', null, ['required'=>true])
            ->add('unitOfMeasure', null, ['required'=>true])
            // ->add('consumptionDelivery')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumptionDeliveryList::class,
        ]);
    }
}
