<?php

namespace App\Form;

use App\Entity\StockList;
use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class StockListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('quantity')
            ->add('unitPrice')
            ->add('codeNumber')
            ->add('product',EntityType::class,[
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->andWhere('u.type = :val')
                              ->setParameter('val', 1)
                              ;
                },
            ])
            ->add('unitOfMeasure')
            // ->add('stock')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockList::class,
        ]);
    }
}
