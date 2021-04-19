<?php

namespace App\Form;

use App\Entity\ConsumptionDelivery;
use App\Entity\ConsumptionRequest;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumptionDeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('receiver', null, ['required'=>true])
            // ->add('requestNo', EntityType::class, [
            //     'class' => ConsumptionRequest::class
            // ])
            ->add('deliveredBy', null, ['required'=>true])
            // ->add('approvedBy', null, ['required'=>true])
            ->add('placeOfDelivery')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumptionDelivery::class,
        ]);
    }
}
