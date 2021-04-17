<?php

namespace App\Form;

use App\Entity\Stock;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class StockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            // ->add('quantity')
            // ->add('totalPrice')
            // ->add('product')
            ->add('store')
            ->add('placeOfDelivery')
            // ->add('paymentVoucherNumber')
            ->add('trackPlateNum')
            ->add('trailerNum')
            ->add('driver')
            ->add('mobile')
            ->add('typeOfDocAndNum')
            // ->add('note')
            ->add('receivedBy')
            ->add('deliveredBy',EntityType::class,[
                'class' => Customer::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->andWhere('u.type = :val')
                              ->setParameter('val', 2)
                              ->orderBy('u.name', 'ASC');
                },
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Stock::class,
        ]);
    }
}
