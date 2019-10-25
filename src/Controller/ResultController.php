<?php

namespace App\Controller;

use App\Dto\Trading\ResultsFilterDto;
use App\Entity\Trading\Result;
use App\Entity\User;
use App\Form\Trading\ResultsFilterType;
use App\Form\Trading\CreateResultType;
use App\Repository\Trading\ResultRepository;
use App\Service\ImageProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends AbstractController
{
    public function index(Request $request): Response
    {
        /** @var ResultRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);
        /** @var User $user */
        $user = $this->getUser();

        $filter = new ResultsFilterDto();
        $form = $this->createForm(ResultsFilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter->setUser($user);

            $data = $repository->filterByDto($filter);
        } else {
            $data = $repository->findBy(compact('user'));
        }

        $form = $form->createView();

        return $this->render('trading/result/index.html.twig', compact('data', 'form'));
    }

    public function indexByUser(User $user, Request $request): Response
    {
        /** @var ResultRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);

        $filter = new ResultsFilterDto();
        $form = $this->createForm(ResultsFilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter->setUser($user);

            $data = $repository->filterByDto($filter);
        } else {
            $data = $repository->findBy(compact('user'));
        }

        $form = $form->createView();

        return $this->render('trading/result/index_by_user.html.twig', compact('data', 'user', 'form'));
    }

    public function create(Request $request, ImageProcessor $imageProcessor): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Result();
        $form = $this->createForm(CreateResultType::class, $entity);

        $form->add('save', SubmitType::class, ['label' => 'save']);
        $form->handleRequest($request);
        $entity->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();

            $imageProcessor->filter($entity->getImage(), ImageProcessor::IMAGE_WIDEN, true);

            return $this->redirectToRoute('results_index');
        }

        return $this->render('trading/result/create.html.twig', ['form' => $form->createView()]);
    }
}
