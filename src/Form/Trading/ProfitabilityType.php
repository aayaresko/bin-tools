<?php

namespace App\Form\Trading;

use App\Dto\Trading\ProfitabilityDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfitabilityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('depositAmount', NumberType::class, ['label' => 'trading.profitability.deposit_amount'])
            ->add('numberOfDays', NumberType::class, ['label' => 'trading.profitability.number_of_days'])
            ->add('numberOfBetsPerDay', NumberType::class, ['label' => 'trading.profitability.number_of_bets_per_day'])
            ->add('betSizeInPercentage', NumberType::class, ['label' => 'trading.profitability.bet_size_in_percentage'])
            ->add('profitableBetsPercentage', NumberType::class, ['label' => 'trading.profitability.profitable_bets_percentage'])
            ->add('profitPerBetPercentage', NumberType::class, ['label' => 'trading.profitability.profit_per_bet_percentage']);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ProfitabilityDto::class,
            'validation_groups' => ['trading_profitability_calculation']
        ]);
    }
}
