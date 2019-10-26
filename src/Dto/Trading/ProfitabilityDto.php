<?php

namespace App\Dto\Trading;

class ProfitabilityDto
{
    public $depositAmount = 200;
    public $numberOfDays = 20;
    public $numberOfBetsPerDay = 5;
    public $betSizeInPercentage = 4;
    public $profitableBetsPercentage = 60;
    public $profitPerBetPercentage = 80;
    public $totalBetsPerMonth;
}