<?php

namespace App\Controller;

use App\Dto\StepsDto;
use App\Dto\TradingProfitabilityDto;
use App\Form\StepsType;
use App\Form\TradingProfitabilityType;
use App\Service\StepsCounter;
use App\Service\TradingProfitabilityCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends AbstractController
{
    public function steps(Request $request, StepsCounter $counter)
    {
        $dto = new StepsDto();
        $form = $this->createForm(StepsType::class, $dto);

        $form->add('calculate', SubmitType::class, ['label' => 'trading.calculate']);
        $form->handleRequest($request);

        $form->isSubmitted() && $form->isValid();

        return $this->render(
            'trading/martingail/steps/new.html.twig',
            [
                'form' => $form->createView(),
                'steps' => $counter->buildSteps($dto),
                'depositAmount' => $dto->depositAmount,
                'totalSpend' => $counter->getTotalSpend($dto)
            ]
        );
    }

    public function tradingProfitability(Request $request, TradingProfitabilityCalculator $calculator)
    {
        $dto = new TradingProfitabilityDto();
        $form = $this->createForm(TradingProfitabilityType::class, $dto);

        $form->add('calculate', SubmitType::class, ['label' => 'trading.calculate']);
        $form->handleRequest($request);

        $form->isSubmitted() && $form->isValid();

        return $this->render(
            'trading/profitability/calculation.html.twig',
            [
                'form' => $form->createView(),
                'dto' => $dto,
                'totalEarnings' => $calculator->getTotalEarnings($dto),
            ]
        );
    }
}
