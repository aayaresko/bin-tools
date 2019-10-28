<?php

namespace App\Service;

use App\Dto\Trading\ProfitabilityDto;

class TradingProfitabilityCalculator
{
    public function getTotalIncome(ProfitabilityDto $dto): float
    {
        return $this->getTotalProfit($dto) + $dto->depositAmount;
    }

    public function getTotalProfit(ProfitabilityDto $dto): float
    {
        $betSize = $this->getBetSize($dto);
        $winningBets = $dto->profitableBetsPercentage / 100 * $dto->numberOfBetsPerDay * $dto->numberOfDays;
        $losingBets = (100 - $dto->profitableBetsPercentage) / 100 * $dto->numberOfBetsPerDay * $dto->numberOfDays;
        $totalProfit = $winningBets * $betSize;
        $totalLoss = $losingBets * $betSize;

        return $totalProfit - $totalLoss;
    }

    public function getBetSize(ProfitabilityDto $dto): float
    {
        return $dto->depositAmount / 100 * $dto->betSizeInPercentage;
    }
}