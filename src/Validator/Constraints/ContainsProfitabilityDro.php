<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsProfitabilityDro extends Constraint
{
    public $tradingIsNotProfitableMessage = 'With {{profitability}}% profitability your trading will not be profitable.';
    public $betSizeIsToBigMessage = 'Making {{betsPerDay}} bets per day with the bet size {{betSize}}% is extremely risky.';

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}