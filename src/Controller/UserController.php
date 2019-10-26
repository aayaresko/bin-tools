<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    const DEFAULT_PAGE_SIZE = 2;

    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        /** @var UserRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);

        $page = $request->query->getInt('page', 1);
        $queryBuilder = $repository->getEnabledQueryBuilder();
        $pagination = $paginator->paginate($queryBuilder, $page, self::DEFAULT_PAGE_SIZE);

        return $this->render('user/index.html.twig', compact('pagination'));
    }
}
