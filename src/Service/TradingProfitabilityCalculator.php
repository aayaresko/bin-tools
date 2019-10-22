<?php

namespace App\Service;

use App\Dto\TradingProfitabilityDto;

class TradingProfitabilityCalculator
{
    /**
     * @param TradingProfitabilityDto $dto
     * @return float
     */
    public function getTotalEarnings(TradingProfitabilityDto $dto)
    {
        $betSize = $dto->depositAmount / 100 * $dto->betSizeInPercentage;
        $winningBets = $dto->profitableBetsPercentage / 100 * $dto->numberOfBetsPerDay * $dto->numberOfDays;

        //"trading.profitability.bet_size_is_to_high"

        return $winningBets * $betSize * $dto->profitPerBetPercentage / 100;
    }
}