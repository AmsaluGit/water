<?php

namespace App\Controller;

use App\Entity\ProductionReport;
use App\Form\ProductionReportType;
use App\Repository\ProductionReportRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/production/report")
 */
class ProductionReportController extends AbstractController
{
    // /**
    //  * @Route("/", name="production_report_index", methods={"GET"})
    //  */
    // public function index(ProductionReportRepository $productionReportRepository): Response
    // {
    //     return $this->render('production_report/index.html.twig', [
    //         'production_reports' => $productionReportRepository->findAll(),
    //     ]);
    // }

    // /**
    //  * @Route("/new", name="production_report_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $productionReport = new ProductionReport();
    //     $form = $this->createForm(ProductionReportType::class, $productionReport);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($productionReport);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('production_report_index');
    //     }

    //     return $this->render('production_report/new.html.twig', [
    //         'production_report' => $productionReport,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="production_report_show", methods={"GET"})
    //  */
    // public function show(ProductionReport $productionReport): Response
    // {
    //     return $this->render('production_report/show.html.twig', [
    //         'production_report' => $productionReport,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="production_report_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, ProductionReport $productionReport): Response
    // {
    //     $form = $this->createForm(ProductionReportType::class, $productionReport);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('production_report_index');
    //     }

    //     return $this->render('production_report/edit.html.twig', [
    //         'production_report' => $productionReport,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="production_report_delete", methods={"POST"})
    //  */
    // public function delete(Request $request, ProductionReport $productionReport): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$productionReport->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($productionReport);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('production_report_index');
    // }
     /**
     * @Route("/", name="production_report_index", methods={"GET","POST"}) 
     */
    public function index(ProductionReportRepository $productionReportRepository,Request $request,PaginatorInterface $paginator): Response
    {

        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $productionReport=$productionReportRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProductionReportType::class, $productionReport);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $productionReport->setDate(new \DateTime());
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('production_report_index');
            }
            $queryBuilder=$productionReportRepository->findProductionReport($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );

            return $this->render('production_report/index.html.twig', [
                'productions' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $productionReport = new ProductionReport();
        $form = $this->createForm(ProductionReportType::class, $productionReport);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $productionReport->setDate(new \DateTime);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($productionReport);
            $entityManager->flush();

            return $this->redirectToRoute('production_report_index');
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$productionReportRepository->findProductionReport($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('production_report/index.html.twig', [
            'productions' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }  
 
    /**
     * @Route("/{id}", name="production_report_delete", methods={"DELETE"})
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
/**
 * @Route("/report", name="Production_report", methods={"POST","GET"})
 */
public function report(Request $request, ProductionReportRepository $ProductReportRepository): Response
{   
    $productReport = $ProductReportRepository->intervalSum(13,1);



        if($request->request->get('range_generate')){
            $start = $request ->request->get('start');
            $end = $request ->request->get('end');

            $productReport = $ProductReportRepository->findDateRangeResultProduction($start, $end);

            return $this->render('production_report/report.html.twig', [
                'ProductionReport' => $productReport,
               ]);
    
              }
         else {
            if($request->request->get('one_month')){

                
                $productReport = $ProductReportRepository->findDateIntervalProduction(1);
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                   ]);
        
            }
            elseif($request->request->get('three_month')){
                
                $productReport = $ProductReportRepository->findDateIntervalProduction(3);
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                   ]);
        
            }
            elseif($request->request->get('six_month')){
                $productReport = $ProductReportRepository->findDateIntervalProduction(6);
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                   ]);
        
            }
            elseif($request->request->get('one_year')){
                $productReport = $ProductReportRepository->findDateIntervalProduction(12);
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                   ]);
        
            
            }
        
            
         }
        
    
         
    
         return $this->render('production_report/report_initial.html.twig', [
           ]);
}

 
}
