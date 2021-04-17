<?php

namespace App\Form;

use App\Entity\ProductDeliveryList;
use App\Entity\Product;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ProductDeliveryListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product',
            EntityType::class,[
                'class'=>Product::class,
                'query_builder' => function(EntityRepository $er){
                    return $er-> createQueryBuilder('u')
                    ->andWhere('u.type = :val')
                    ->setParameter('val',2)
                    ->orderBy('u.name','ASC');
                }
            ]
            )
            ->add('specification')
            ->add('quantity')
            ->add('weight')
            ->add('unitPrice')
            // ->add('productDelivery',EntityType::class,[
            //     'class' =>ProductDelivery::class
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductDeliveryList::class,
        ]);
    }
    
}
