<?php

namespace App\Form;

use App\Entity\ConsumptionApproval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ConsumptionApprovalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('approvedQuantity')
            ->add('remark')
            // ->add('unitOfMeasure')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ConsumptionApproval::class,
        ]);
    }
}
