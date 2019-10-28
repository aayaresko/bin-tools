<?php

namespace App\Controller;

use App\Dto\Trading\ResultsFilterDto;
use App\Entity\Tag;
use App\Entity\Trading\Result;
use App\Entity\User;
use App\Form\Trading\ResultsFilterType;
use App\Form\Trading\CreateResultType;
use App\Repository\TagRepository;
use App\Repository\Trading\ResultRepository;
use App\Service\ImageProcessor;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ResultController extends AbstractController
{
    const DEFAULT_PAGE_SIZE = 10;

    public function index(PaginatorInterface $paginator, Request $request): Response
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

            $queryBuilder = $repository->getFilterByDtoQueryBuilder($filter);
        } else {
            $queryBuilder = $repository->getByUserQueryBuilder($user);
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $paginator->paginate($queryBuilder, $page, self::DEFAULT_PAGE_SIZE);
        $form = $form->createView();

        return $this->render('trading/result/index.html.twig', compact('pagination', 'form'));
    }

    public function searchByUser(User $user, PaginatorInterface $paginator, Request $request): Response
    {
        /** @var ResultRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);

        $filter = new ResultsFilterDto();
        $form = $this->createForm(ResultsFilterType::class, $filter);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $filter->setUser($user);

            $queryBuilder = $repository->getFilterByDtoQueryBuilder($filter);
        } else {
            $queryBuilder = $repository->getByUserQueryBuilder($user);
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $paginator->paginate($queryBuilder, $page, self::DEFAULT_PAGE_SIZE);
        $form = $form->createView();

        return $this->render('trading/result/search/user.html.twig', compact('pagination', 'user', 'form'));
    }

    public function searchByTag(string $value, PaginatorInterface $paginator, Request $request): Response
    {
        if (!$value) {
            throw new NotFoundHttpException();
        }

        /** @var ResultRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);
        
        $filter = new ResultsFilterDto();
        $form = $this->createForm(ResultsFilterType::class, $filter);
        $queryBuilder = $repository->getFilterByTagQueryBuilder($value);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $queryBuilder = $repository->attachResultsFilterCriteria($queryBuilder, $filter);
        }

        $page = $request->query->getInt('page', 1);
        $pagination = $paginator->paginate($queryBuilder, $page, self::DEFAULT_PAGE_SIZE);
        $form = $form->createView();

        return $this->render('trading/result/search/tag.html.twig', compact('pagination', 'value', 'form'));
    }

    public function create(Request $request, ImageProcessor $imageProcessor): Response
    {
        $em = $this->getDoctrine()->getManager();
        /** @var TagRepository $tagRepository */
        $tagRepository = $em->getRepository(Tag::class);

        $entity = new Result();
        $form = $this->createForm(CreateResultType::class, $entity);

        $form->handleRequest($request);
        $entity->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();

            if ($entity->getImage()) {
                $imageProcessor->filter($entity->getImage(), ImageProcessor::RESULT_IMAGE_WIDEN, true);
            }

            return $this->redirectToRoute('results_index');
        }


        $availableTags = $tagRepository->findUnique();

        return $this->render(
            'trading/result/create.html.twig',
            [
                'form' => $form->createView(),
                'availableTags' => $availableTags,
                'selectedTags' => $entity->getTags()
            ]
        );
    }
}
