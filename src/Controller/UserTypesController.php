<?php

namespace App\Controller;

use App\Entity\UserTypes;
use App\Form\UserTypesType;
use App\Repository\UserTypesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user/types")
 */
class UserTypesController extends AbstractController
{
    /**
     * @Route("/", name="user_types_index", methods={"GET"})
     */
    public function index(UserTypesRepository $userTypesRepository): Response
    {
        return $this->render('user_types/index.html.twig', [
            'user_types' => $userTypesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_types_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $userType = new UserTypes();
        $form = $this->createForm(UserTypesType::class, $userType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userType);
            $entityManager->flush();

            return $this->redirectToRoute('user_types_index');
        }

        return $this->render('user_types/new.html.twig', [
            'user_type' => $userType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_types_show", methods={"GET"})
     */
    public function show(UserTypes $userType): Response
    {
        return $this->render('user_types/show.html.twig', [
            'user_type' => $userType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_types_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UserTypes $userType): Response
    {
        $form = $this->createForm(UserTypesType::class, $userType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_types_index');
        }

        return $this->render('user_types/edit.html.twig', [
            'user_type' => $userType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_types_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UserTypes $userType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$userType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($userType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_types_index');
    }
}
