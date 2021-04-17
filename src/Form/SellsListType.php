<?php

namespace App\Form;

use App\Entity\SellsList;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SellsListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('specification')
            ->add('quantity')
            ->add('weight')
            ->add('unitPrice')
            ->add('product',EntityType::class,[
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->andWhere('u.type = :val')
                              ->setParameter('val', 1)
                              ;
                },
            ])
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
