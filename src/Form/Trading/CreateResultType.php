<?php

namespace App\Form\Trading;

use App\Entity\Trading\Result;
use App\Form\TagType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CreateResultType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('openingQuote', MoneyType::class, [
                'required' => false,
                'label' => 'trading.result.opening_quote',
                'currency' => 'USD'
            ])
            ->add('closingQuote', MoneyType::class, [
                'required' => false,
                'label' => 'trading.result.closing_quote',
                'currency' => 'USD'
            ])
            ->add('spent', IntegerType::class, ['label' => 'trading.result.spent'])
            ->add('profit', MoneyType::class, [
                'label' => 'trading.result.profit',
                'currency' => 'USD'
            ])
            ->add('notes', TextareaType::class, ['label' => 'trading.result.notes', 'required' => false])
            ->add('date', DateType::class, [
                'label' => 'trading.result.date',
                'widget' => 'single_text',
                'format' => 'dd.mm.yyyy',
                'attr' => [
                    'class' => 'js-datepicker',
                    'autocomplete' => 'off'
                ],
            ])
            ->add('mediaFile', FileType::class, ['label' => 'trading.result.image', 'required' => false])
            ->add('tags', CollectionType::class, [
                'label' => false,
                'entry_type' => TagType::class,
                'entry_options' => [
                    'label' => false
                ],
                'allow_add' => true,
                'allow_delete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Result::class,
            'validation_groups' => ['create_trading_result']
        ]);
    }
}
