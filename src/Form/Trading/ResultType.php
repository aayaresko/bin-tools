<?php

namespace App\Form\Trading;

use App\Entity\Trading\Result;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('openingQuote', MoneyType::class, [
                'label' => 'trading.results.opening_quote',
                'currency' => 'USD'
            ])
            ->add('closingQuote', MoneyType::class, [
                'label' => 'trading.results.closing_quote',
                'currency' => 'USD'
            ])
            ->add('spent', IntegerType::class, ['label' => 'trading.results.spent'])
            ->add('profit', MoneyType::class, [
                'label' => 'trading.results.profit',
                'currency' => 'USD'
            ])
            ->add('notes', TextareaType::class, ['label' => 'trading.results.notes', 'required' => false])
            ->add('date', DateType::class, [
                'label' => 'trading.results.date',
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Result::class,
            'validation_group' => 'create_trading_result'
        ]);
    }
}
