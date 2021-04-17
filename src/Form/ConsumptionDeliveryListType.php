<?php

namespace App\Form;
use App\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
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
            ->add('product',EntityType::class,[
                'class' => Product::class,
                'query_builder' => function (EntityRepository $er){
                    return $er->createQueryBuilder('p')
                              ->andWhere('p.type = :val')
                              ->setParameter('val', 1)
                              ->orderBy('p.name', 'ASC');
                },   
            ])
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
