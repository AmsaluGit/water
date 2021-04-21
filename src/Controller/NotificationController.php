<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConsumptionRequestRepository;
use App\Repository\ConsumptionDeliveryRepository;
use App\Repository\SellsRepository;
use App\Repository\StockRequestRepository;
use App\Repository\StockRepository;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification", methods={"POST", "GET"})
     */
    public function index(ConsumptionRequestRepository $consumptionRequestRepository, Request $request, StockRequestRepository $stockRequestRepository, StockRepository $stockRepository, ConsumptionDeliveryRepository $consumptionDeliveryRepository, SellsRepository $sellsRepository): Response
    {
      

    //   $consumptionRequest =$consumptionRequestRepository->findBy('approvalStatus',null);
      $consumptionRequest = $consumptionRequestRepository->findBy(['approvalStatus' => 1]);
      $newConsumptionRequest = count($consumptionRequest);
      
      $stockRequest = $stockRequestRepository->findBy(['approvalStatus' => 1]);
      $newStockRequest = count($stockRequest);

      $stock = $stockRepository->findBy(['approvalStatus' => 1]);
      $newStock = count($stock);

      $consumptionDelivery = $consumptionDeliveryRepository->findBy(['approvalStatus' => 1]);
      $newConsumptionDelivery = count($consumptionDelivery);

    //   $sells = $sellsRepository->findBy(['approvalStatus' => 1]);
      $totalNotifications = $newConsumptionRequest + $newStockRequest + $newStock + $newConsumptionDelivery;

    //   $students = $this->getDoctrine() 
    //                    ->getRepository('AppBundle:Department') 
    //                    ->findAll();


  
        if ( $request->isXmlHttpRequest()) {  
            $jsonData = array(
                'consumptionRequest' => $newConsumptionRequest,
                'stockRequest' =>  $newStockRequest,
                'stock' => $newStock,
                'consumptionDelivery' => $newConsumptionDelivery,
                'total' => $totalNotifications
            );  
            return new JsonResponse($jsonData); 
        }
        // if (!$request->isXmlHttpRequest()) { 
        //     return new JsonResponse(array(
        //         'status' => 'Error',
        //         'message' => 'Error'),
        //     400);
        // }
        // if($request->request->get('some_var_name')){
        //     //make something curious, get some unbelieveable data
        //     $arrData = ['output' => 'here the result which will appear in div'];
        //     return new JsonResponse($arrData);
        // }
       
      

        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
                'consumptionRequest' => $newConsumptionRequest,
                'stockRequest' =>  $newStockRequest,
                'stock' => $newStock,
                'consumptionDelivery' => $newConsuptionDelivery
        ]);
    }
} 