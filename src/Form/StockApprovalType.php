<?php

namespace App\Form;

use App\Entity\StockApproval;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StockApprovalType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('approvedQuantity')
            //->add('dateOfApproval')
            //->add('approvalResponse')
            ->add('remark')
            //->add('approvalLevel')
            //->add('stock')
            //->add('approvedBy')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => StockApproval::class,
        ]);
    }
}
