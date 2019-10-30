<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Repository\TagRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends AbstractController
{
    const DEFAULT_PAGE_SIZE = 10;

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        /** @var TagRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(Tag::class);

        $page = $request->query->getInt('page', 1);
        $queryBuilder = $repository->createQueryBuilder('t');
        $pagination = $paginator->paginate($queryBuilder, $page, self::DEFAULT_PAGE_SIZE);

        return $this->render('tag/index.html.twig', compact('pagination'));
    }
}
