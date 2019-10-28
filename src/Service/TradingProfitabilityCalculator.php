<?php

namespace App\Service;

use App\Dto\Trading\ProfitabilityDto;

class TradingProfitabilityCalculator
{
    /**
     * @param ProfitabilityDto $dto
     * @return float
     */
    public function getTotalEarnings(ProfitabilityDto $dto): float
    {
        $betSize = $this->getBetSize($dto);
        $winningBets = $dto->profitableBetsPercentage / 100 * $dto->numberOfBetsPerDay * $dto->numberOfDays;

        //"trading.profitability.bet_size_is_to_high"

        return $winningBets * $betSize * $dto->profitPerBetPercentage / 100;
    }

    public function getBetSize(ProfitabilityDto $dto): float
    {
        return $dto->depositAmount / 100 * $dto->betSizeInPercentage;
    }
}