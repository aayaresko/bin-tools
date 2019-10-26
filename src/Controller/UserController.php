<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{
    public function index(): Response
    {
        /** @var UserRepository $repository */
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);

        return $this->render('user/index.html.twig', ['data' => $repository->findBy(['enabled' => true])]);
    }
}
