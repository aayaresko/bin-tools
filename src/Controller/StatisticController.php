<?php

namespace App\Controller;

use App\Dto\StepsDto;
use App\Form\StepsType;
use App\Service\StepsCounter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

class StatisticController extends AbstractController
{
    public function steps(Request $request, StepsCounter $counter)
    {
        $dto = new StepsDto();
        $form = $this->createForm(StepsType::class, $dto);

        $form->add('calculate', SubmitType::class, ['label' => 'steps.calculate']);
        $form->handleRequest($request);

        $form->isSubmitted() && $form->isValid();

        $counter->buildSteps($dto);

        return $this->render(
            'statistic/steps/new.html.twig',
            [
                'form' => $form->createView(),
                'steps' => $counter->buildSteps($dto),
                'depositAmount' => $dto->depositAmount,
                'totalSpend' => $counter->getTotalSpend($dto)
            ]
        );
    }
}
