<?php

namespace App\Form;

use App\Entity\ConsumptionRequest;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumptionRequestType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('requestedDate')
            ->add('section',null, [
                'required'=>true,
                ]);
            // ->add('remark')
            // // ->add('requester')
            // // ->add('product')
            // ->add('unitOfMeasure')
            // ->add('dueDate', DateType::class, [
            //     'required'=> false
            // ])
        
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumptionRequest::class,
        ]);
    }
}
