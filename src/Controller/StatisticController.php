<?php

namespace App\Controller;

use App\Dto\StepsDto;
use App\Dto\Trading\ProfitabilityDto;
use App\Dto\Trading\ResultsFilterDto;
use App\Entity\Tag;
use App\Entity\Trading\Result;
use App\Entity\User;
use App\Form\StepsType;
use App\Form\Trading\ProfitabilityType;
use App\Form\Trading\ResultsFilterType;
use App\Repository\TagRepository;
use App\Repository\Trading\ResultRepository;
use App\Service\StepsCounter;
use App\Service\TradingProfitabilityCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    public function calculateTradingProfitability(Request $request, TradingProfitabilityCalculator $calculator): Response
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
                'totalIncome' => $calculator->getTotalIncome($dto),
                'totalProfit' => $calculator->getTotalProfit($dto),
                'betSize' => $calculator->getBetSize($dto),
            ]
        );
    }

    public function calculateTradingProfitabilityByUser(User $user, Request $request): Response
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

        return $this->render('trading/profitability/calculation/user.html.twig', compact('data', 'form', 'user'));
    }

    public function calculateTradingProfitabilityByTag(string $value, Request $request): Response
    {
        if (!$value) {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        /** @var ResultRepository $repository */
        $repository = $em->getRepository(Result::class);
        /** @var TagRepository $tagRepository */
        $tagRepository = $em->getRepository(Tag::class);

        $filter = new ResultsFilterDto();
        $form = $this->createForm(ResultsFilterType::class, $filter);

        $form->handleRequest($request);

        $filter->tagValue = $value;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $repository->calculateUserProfitabilityFromDto($filter);
        }

        $form = $form->createView();
        $availableTags = $tagRepository->findAll();

        return $this->render(
            'trading/profitability/calculation/tag.html.twig',
            compact('data', 'form', 'user', 'availableTags', 'value'));
    }
}
