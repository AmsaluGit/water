<?php

namespace App\Controller;

use App\Entity\ConsumptionDeliveryList;
use App\Form\ConsumptionDeliveryListType;
use App\Repository\ConsumptionDeliveryListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/consumption/delivery/list")
 */
class ConsumptionDeliveryListController extends AbstractController
{
    /**
     * @Route("/", name="consumption_delivery_list_index", methods={"GET"})
     */
    public function index(ConsumptionDeliveryListRepository $consumptionDeliveryListRepository): Response
    {
        return $this->render('consumption_delivery_list/index.html.twig', [
            'consumption_delivery_lists' => $consumptionDeliveryListRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="consumption_delivery_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $consumptionDeliveryList = new ConsumptionDeliveryList();
        $form = $this->createForm(ConsumptionDeliveryListType::class, $consumptionDeliveryList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consumptionDeliveryList);
            $entityManager->flush();

            return $this->redirectToRoute('consumption_delivery_list_index');
        }

        return $this->render('consumption_delivery_list/new.html.twig', [
            'consumption_delivery_list' => $consumptionDeliveryList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="consumption_delivery_list_show", methods={"GET"})
     */
    public function show(ConsumptionDeliveryList $consumptionDeliveryList): Response
    {
        return $this->render('consumption_delivery_list/show.html.twig', [
            'consumption_delivery_list' => $consumptionDeliveryList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="consumption_delivery_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ConsumptionDeliveryList $consumptionDeliveryList): Response
    {
        $form = $this->createForm(ConsumptionDeliveryListType::class, $consumptionDeliveryList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('consumption_delivery_list_index');
        }

        return $this->render('consumption_delivery_list/edit.html.twig', [
            'consumption_delivery_list' => $consumptionDeliveryList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="consumption_delivery_list_delete", methods={"POST"})
     */
    public function delete(Request $request, ConsumptionDeliveryList $consumptionDeliveryList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consumptionDeliveryList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($consumptionDeliveryList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('consumption_delivery_list_index');
    }
}
