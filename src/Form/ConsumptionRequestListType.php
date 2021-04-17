<?php

namespace App\Form;

use App\Entity\ConsumptionRequestList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
class ConsumptionRequestListType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('product', EntityType::class,[
                'class' => Product::class,
                'query_builder' => function (EntityReposistory $er){
                    return $er->createQueryBuilder('p')
                              ->andWhere('p.type = :val')
                              ->setParameter('val', 1)
                              ->orderBy('p.name', 'ASC');
                },   
            ])
            ->add('codeNumber')
            ->add('unitOfMeasure',null, [
                'required'=>true,
                ])
            ->add('quantity',);

            // ->add('approvedQuantity')
            // ->add('issue')
            // ->add('consumptionRequest')
            // ->add('available')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumptionRequestList::class,
        ]);
    }
}
