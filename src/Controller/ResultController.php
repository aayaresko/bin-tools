<?php

namespace App\Controller;

use App\Entity\Trading\Result;
use App\Entity\User;
use App\Form\Trading\ResultType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResultController extends AbstractController
{
    public function index(): Response
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);
        $data = $repository->findBy(['user' => $this->getUser()]);

        return $this->render('trading/result/index.html.twig', compact('data'));
    }

    public function indexByUser(User $user): Response
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(Result::class);
        $data = $repository->findBy(compact('user'));

        return $this->render('trading/result/index_by_user.html.twig', compact('data', 'user'));
    }

    public function create(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $entity = new Result();
        $form = $this->createForm(ResultType::class, $entity);

        $form->add('save', SubmitType::class, ['label' => 'save']);
        $form->handleRequest($request);
        $entity->setUser($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirectToRoute('results_index');
        }

        return $this->render('trading/result/create.html.twig', ['form' => $form->createView()]);
    }
}
