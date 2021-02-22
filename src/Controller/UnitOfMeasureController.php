<?php

namespace App\Controller;

use App\Entity\UnitOfMeasure;
use App\Form\UnitOfMeasureType;
use App\Repository\UnitOfMeasureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/unitofmeasure")
 */
class UnitOfMeasureController extends AbstractController
{
    /**
     * @Route("/", name="unit_of_measure_index", methods={"GET"})
     */
    public function index(UnitOfMeasureRepository $unitOfMeasureRepository): Response
    {
        return $this->render('unit_of_measure/index.html.twig', [
            'unit_of_measures' => $unitOfMeasureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="unit_of_measure_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $unitOfMeasure = new UnitOfMeasure();
        $form = $this->createForm(UnitOfMeasureType::class, $unitOfMeasure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($unitOfMeasure);
            $entityManager->flush();

            return $this->redirectToRoute('unit_of_measure_index');
        }

        return $this->render('unit_of_measure/new.html.twig', [
            'unit_of_measure' => $unitOfMeasure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="unit_of_measure_show", methods={"GET"})
     */
    public function show(UnitOfMeasure $unitOfMeasure): Response
    {
        return $this->render('unit_of_measure/show.html.twig', [
            'unit_of_measure' => $unitOfMeasure,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="unit_of_measure_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, UnitOfMeasure $unitOfMeasure): Response
    {
        $form = $this->createForm(UnitOfMeasureType::class, $unitOfMeasure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('unit_of_measure_index');
        }

        return $this->render('unit_of_measure/edit.html.twig', [
            'unit_of_measure' => $unitOfMeasure,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="unit_of_measure_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UnitOfMeasure $unitOfMeasure): Response
    {
        if ($this->isCsrfTokenValid('delete'.$unitOfMeasure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($unitOfMeasure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('unit_of_measure_index');
    }
}
