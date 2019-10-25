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
                'label' => 'trading.results.date',
                'widget' => 'single_text',
            ])
            ->add('dateTo', DateType::class, [
                'label' => 'trading.results.filter.date_to',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ResultsFilterDto::class,
            'validation_group' => 'results_filter'
        ]);
    }
}
