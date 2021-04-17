<?php

namespace App\Form;

use App\Entity\ProductDelivery;
// use DateInterval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('choice', RadioType::class,[
                'label'  => 'Raw Material Report:'])
            ->add('choice', RadioType::class,[
                'label' => 'Production Report'
           
            ])
            ->add('one_Month' , ButtonType::class)
            ->add('Three_Month', ButtonType::class)
            ->add('Six_Month', ButtonType::class)
            ->add('One_Year', ButtonType::class)
            // ->add('roles')
            ->add('Date',DateIntervalType::class, [
                'labels' => [
                    'from' => null,
                    'to' => null]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProductDelivery::class,
        ]);
    }
}
