<?php

namespace App\Form;

use App\Entity\SellsList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SellsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specification')
            ->add('quantity')
            ->add('weight')
            ->add('unitPrice')
            ->add('product')
            // ->add('sells')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SellsList::class,
        ]);
    }
}
