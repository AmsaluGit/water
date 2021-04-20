<?php

namespace App\Controller;

use App\Entity\ProductionReport;
use App\Form\ProductionReportType;
use App\Repository\ProductionReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/production/report")
 */
class ProductionReportController extends AbstractController
{
    /**
     * @Route("/", name="production_report_index", methods={"GET"})
     */
    public function index(ProductionReportRepository $productionReportRepository): Response
    {
        return $this->render('production_report/index.html.twig', [
            'production_reports' => $productionReportRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="production_report_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $productionReport = new ProductionReport();
        $form = $this->createForm(ProductionReportType::class, $productionReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productionReport);
            $entityManager->flush();

            return $this->redirectToRoute('production_report_index');
        }

        return $this->render('production_report/new.html.twig', [
            'production_report' => $productionReport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="production_report_show", methods={"GET"})
     */
    public function show(ProductionReport $productionReport): Response
    {
        return $this->render('production_report/show.html.twig', [
            'production_report' => $productionReport,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="production_report_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, ProductionReport $productionReport): Response
    {
        $form = $this->createForm(ProductionReportType::class, $productionReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('production_report_index');
        }

        return $this->render('production_report/edit.html.twig', [
            'production_report' => $productionReport,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="production_report_delete", methods={"POST"})
     */
    public function delete(Request $request, ProductionReport $productionReport): Response
    {
        if ($this->isCsrfTokenValid('delete'.$productionReport->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($productionReport);
            $entityManager->flush();
        }

        return $this->redirectToRoute('production_report_index');
    }
}
