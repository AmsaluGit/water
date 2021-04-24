<?php

namespace App\Controller;

use App\Entity\ProductionReport;
use App\Form\ProductionReportType;
use App\Repository\ProductionReportRepository;
use App\Repository\MaterialRecordRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/productionReport")
 */
class ProductionReportController extends AbstractController
{
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
            $productionReport->setDate(new \DateTime());
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
public function report(Request $request, ProductionReportRepository $ProductReportRepository, MaterialRecordRepository $materialRecordRepository): Response
{   


    if($request->request->get('radio')== 1){
        if($request->request->get('range_generate')){
            $start = $request ->request->get('start');
            $end = $request ->request->get('end');

            $productReport = $ProductReportRepository->findDateRangeResultProduction($start, $end);
            $p1_sum = $ProductReportRepository->RangeSum($start,$end,1);    
            $p2_sum = $ProductReportRepository->RangeSum($start,$end,2);    
            $p3_sum = $ProductReportRepository->RangeSum($start,$end,3);    
            $p4_sum = $ProductReportRepository->RangeSum($start,$end,4);    
            return $this->render('production_report/report.html.twig', [
                'ProductionReport' => $productReport,
                'from_'=>$start,
                'to_'=>$end,
                'size' => null,
                'p1' => $p1_sum,
                'p2' => $p2_sum,
                'p3' => $p3_sum,
                'p4' => $p4_sum

               ]);
    
              }
         else {
            if($request->request->get('one_month')){

                
            $productReport = $ProductReportRepository->findDateIntervalProduction(1);
            $p1_sum = $ProductReportRepository->IntervalSum(1,1);    
            $p2_sum = $ProductReportRepository->IntervalSum(1,2);    
            $p3_sum = $ProductReportRepository->IntervalSum(1,3);    
            $p4_sum = $ProductReportRepository->IntervalSum(1,4);    
            return $this->render('production_report/report.html.twig', [
                'ProductionReport' => $productReport,
                'from_'=>null,
                'to_'=>null,
                'size' => 1,
                'p1' => $p1_sum,
                'p2' => $p2_sum,
                'p3' => $p3_sum,
                'p4' => $p4_sum

               ]);
        
            }
            elseif($request->request->get('three_month')){
                
                $productReport = $ProductReportRepository->findDateIntervalProduction(3);
                $p1_sum = $ProductReportRepository->IntervalSum(3,1);    
                $p2_sum = $ProductReportRepository->IntervalSum(3,2);    
                $p3_sum = $ProductReportRepository->IntervalSum(3,3);    
                $p4_sum = $ProductReportRepository->IntervalSum(3,4);    
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                    'from_'=>null,
                    'to_'=>null,
                    'size' => 3,
                    'p1' => $p1_sum,
                    'p2' => $p2_sum,
                    'p3' => $p3_sum,
                    'p4' => $p4_sum
    
                   ]);
        
            }
            elseif($request->request->get('six_month')){
                $productReport = $ProductReportRepository->findDateIntervalProduction(6);
                $p1_sum = $ProductReportRepository->IntervalSum(6,1);    
                $p2_sum = $ProductReportRepository->IntervalSum(6,2);    
                $p3_sum = $ProductReportRepository->IntervalSum(6,3);    
                $p4_sum = $ProductReportRepository->IntervalSum(6,4);    
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                    'from_'=>null,
                    'to_'=>null,
                    'size' => 6,
                    'p1' => $p1_sum,
                    'p2' => $p2_sum,
                    'p3' => $p3_sum,
                    'p4' => $p4_sum
    
                   ]);
        
            }
            elseif($request->request->get('one_year')){

                $productReport = $ProductReportRepository->findDateIntervalProduction(12);
                $p1_sum = $ProductReportRepository->IntervalSum(12,1);    
                $p2_sum = $ProductReportRepository->IntervalSum(12,2);    
                $p3_sum = $ProductReportRepository->IntervalSum(12,3);    
                $p4_sum = $ProductReportRepository->IntervalSum(12,4);    
                return $this->render('production_report/report.html.twig', [
                    'ProductionReport' => $productReport,
                    'from_'=>null,
                    'to_'=>null,
                    'size' => 12,
                    'p1' => $p1_sum,
                    'p2' => $p2_sum,
                    'p3' => $p3_sum,
                    'p4' => $p4_sum
    
                   ]);
        
            
            }
        
            
         }
    }

        elseif($request->request->get('radio')==2){
            if($request->request->get('range_generate')){
                $start = $request ->request->get('start');
                $end = $request ->request->get('end');
    
                $MaterialRecord = $materialRecordRepository->findDateRangeResultMaterial($start, $end);
                $p1_sum = $materialRecordRepository->RangeSumMaterial($start,$end,1);    
                $p2_sum = $materialRecordRepository->RangeSumMaterial($start,$end,2);    
                $p3_sum = $materialRecordRepository->RangeSumMaterial($start,$end,3);    
                $p4_sum = $materialRecordRepository->RangeSumMaterial($start,$end,4);    
                return $this->render('material_record/2.html.twig', [
                    'materialReport' => $MaterialRecord,
                    'from_'=>$start,
                    'to_'=>$end,
                    'size' => null,
                    'p1' => $p1_sum,
                    'p2' => $p2_sum,
                    'p3' => $p3_sum,
                    'p4' => $p4_sum
    
                   ]);
        
                  }
             else {
                if($request->request->get('one_month')){
                
                    
                $MaterialRecord = $materialRecordRepository->findDateIntervalMaterial(1);
                $p1_sum = $materialRecordRepository->IntervalSumMaterial(1,1);    
                $p2_sum = $materialRecordRepository->IntervalSumMaterial(1,2);    
                $p3_sum = $materialRecordRepository->IntervalSumMaterial(1,3);    
                $p4_sum = $materialRecordRepository->IntervalSumMaterial(1,4);    
                return $this->render('material_record/2.html.twig', [
                    'materialReport' => $MaterialRecord,
                    'from_'=>null,
                    'to_'=>null,
                    'size' => 1,
                    'p1' => $p1_sum,
                    'p2' => $p2_sum,
                    'p3' => $p3_sum,
                    'p4' => $p4_sum
    
                   ]);
            
                }
                elseif($request->request->get('three_month')){
                    
                    $MaterialRecord = $materialRecordRepository->findDateIntervalMaterial(3);
                    $p1_sum = $materialRecordRepository->IntervalSumMaterial(3,1);    
                    $p2_sum = $materialRecordRepository->IntervalSumMaterial(3,2);    
                    $p3_sum = $materialRecordRepository->IntervalSumMaterial(3,3);    
                    $p4_sum = $materialRecordRepository->IntervalSumMaterial(3,4);    
                    return $this->render('material_record/2.html.twig', [
                        'materialReport' => $MaterialRecord,
                        'from_'=>null,
                        'to_'=>null,
                        'size' => 3,
                        'p1' => $p1_sum,
                        'p2' => $p2_sum,
                        'p3' => $p3_sum,
                        'p4' => $p4_sum
        
                       ]);
            
                }
                elseif($request->request->get('six_month')){
                    $MaterialRecord = $materialRecordRepository->findDateIntervalMaterial(6);
                    $p1_sum = $materialRecordRepository->IntervalSumMaterial(6,1);    
                    $p2_sum = $materialRecordRepository->IntervalSumMaterial(6,2);    
                    $p3_sum = $materialRecordRepository->IntervalSumMaterial(6,3);    
                    $p4_sum = $materialRecordRepository->IntervalSumMaterial(6,4);    
                    return $this->render('material_record/2.html.twig', [
                        'materialReport' => $MaterialRecord,
                        'p1' => $p1_sum,
                        'p2' => $p2_sum,
                        'p3' => $p3_sum,
                        'p4' => $p4_sum,
                        'from_'=>null,
                        'to_'=>null,
                        'size' => 6
        
                       ]);
            
                }
                elseif($request->request->get('one_year')){
    
                    $MaterialRecord = $materialRecordRepository->findDateIntervalMaterial(12);
                    $p1_sum = $materialRecordRepository->IntervalSumMaterial(12,1);    
                    $p2_sum = $materialRecordRepository->IntervalSumMaterial(12,2);    
                    $p3_sum = $materialRecordRepository->IntervalSumMaterial(12,3);    
                    $p4_sum = $materialRecordRepository->IntervalSumMaterial(12,4);    
                    return $this->render('material_record/2.html.twig', [
                        'materialReport' => $MaterialRecord,
                        'p1' => $p1_sum,
                        'from_'=>null,
                        'to_'=>null,
                        'size' => 12,    
                        'p2' => $p2_sum,
                        'p3' => $p3_sum,
                        'p4' => $p4_sum
        
                       ]);
            
                
                }
            
                
             }


        } 
         
    
         return $this->render('production_report/report_initial.html.twig', [
           ]);
}

 
}
