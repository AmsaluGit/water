<?php

namespace App\Form\Filter;

use App\Entity\Requests;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RequestFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('requestedDate')
            ->add('requester')
            ->add('approval1')
            ->add('approval2')
            ->add('approval3')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Requests::class,
        ]);
    }
}
