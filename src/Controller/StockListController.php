<?php

namespace App\Controller;

use App\Entity\StockList;
use App\Form\StockListType;
use App\Repository\StockListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/stock/list")
 */
class StockListController extends AbstractController
{
    /**
     * @Route("/", name="stock_listindex", methods={"GET"})
     */
    public function index(StockListRepository $stockListRepository): Response
    {
        return $this->render('stock_list/index.html.twig', [
            'stock_lists' => $stockListRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="stock_list_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $stockList = new StockList();
        $form = $this->createForm(StockListType::class, $stockList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stockList);
            $entityManager->flush();

            return $this->redirectToRoute('stock_list_index');
        }

        return $this->render('stock_list/new.html.twig', [
            'stock_list' => $stockList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_list_show", methods={"GET"})
     */
    public function show(StockList $stockList): Response
    {
        return $this->render('stock_list/show.html.twig', [
            'stock_list' => $stockList,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="stock_list_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, StockList $stockList): Response
    {
        $form = $this->createForm(StockListType::class, $stockList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('stock_list_index');
        }

        return $this->render('stock_list/edit.html.twig', [
            'stock_list' => $stockList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="stock_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, StockList $stockList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stockList);
            $entityManager->flush();
        }

        return $this->redirectToRoute('stock_list_index');
    }
}
