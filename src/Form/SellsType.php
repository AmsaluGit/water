<?php

namespace App\Form;

use App\Entity\Sells;
use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Repository\CustomerRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
class SellsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('placeOfDelivery')
            ->add('paymentVoucherNumber')
            ->add('plateNumber')
            ->add('trailNumber')
            ->add('driver')
            ->add('phone')
            // ->add('note')
            ->add('receivedBy',EntityType::class,[
                'class' => Customer::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                              ->andWhere('u.type = :val')
                              ->setParameter('val', 1)
                              ->orderBy('u.name', 'ASC');
                },
            ])
            // ->add('deliveredBy')
            // ->add('approvedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sells::class,
        ]);
    }
}
