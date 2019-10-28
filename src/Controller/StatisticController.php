<?php

namespace App\Controller;

use App\Dto\StepsDto;
use App\Dto\Trading\ProfitabilityDto;
use App\Dto\Trading\ResultsFilterDto;
use App\Entity\Trading\Result;
use App\Entity\User;
use App\Form\StepsType;
use App\Form\Trading\ProfitabilityType;
use App\Form\Trading\ResultsFilterType;
use App\Repository\Trading\ResultRepository;
use App\Service\StepsCounter;
use App\Service\TradingProfitabilityCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StatisticController extends AbstractController
{
    public function steps(Request $request, StepsCounter $counter): Response
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

    public function tradingProfitability(Request $request, TradingProfitabilityCalculator $calculator): Response
    {
        $dto = new ProfitabilityDto();
        $form = $this->createForm(ProfitabilityType::class, $dto);

        $form->add('calculate', SubmitType::class, ['label' => 'trading.calculate']);
        $form->handleRequest($request);

        $form->isSubmitted() && $form->isValid();

        return $this->render(
            'trading/profitability/calculation.html.twig',
            [
                'form' => $form->createView(),
                'dto' => $dto,
                'totalEarnings' => $calculator->getTotalEarnings($dto),
                'betSize' => $calculator->getBetSize($dto),
            ]
        );
    }

    public function userTradingProfitability(User $user, Request $request): Response
    {
        /** @var ResultRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);

        $filter = new ResultsFilterDto();
        $form = $this->createForm(ResultsFilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter->setUser($user);

            $data = $repository->calculateUserProfitabilityFromDto($filter);
        }

        $form = $form->createView();

        return $this->render('trading/profitability/user_calculation.html.twig', compact('data', 'form', 'user'));
    }
}
