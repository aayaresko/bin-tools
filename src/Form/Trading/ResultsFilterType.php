<?php

namespace App\Form\Trading;

use App\Dto\Trading\ResultsFilterDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultsFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('dateFrom', DateType::class, [
                'label' => 'trading.result.filter.date_from',
                'widget' => 'single_text',
                'format' => 'dd.mm.yyyy',
                'attr' => [
                    'class' => 'input-sm',
                    'name' => 'start',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'trading.result.filter.date_to',
                'widget' => 'single_text',
                'format' => 'dd.mm.yyyy',
                'attr' => [
                    'class' => 'input-sm',
                    'name' => 'end',
                    'autocomplete' => 'off'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResultsFilterDto::class,
            'validation_groups' => ['result_filter']
        ]);
    }
}
