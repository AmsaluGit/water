<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConsumptionRequestRepository;
use App\Repository\SellsRepository;
use App\Repository\StockRequestRepository;

class NotificationController extends AbstractController
{
    /**
     * @Route("/notification", name="notification")
     */
    public function index(ConsumptionRequestRepository $consumptionRequestRepository, Request $request, StockRequestRepository $stockRequestRepository, SellsRepository $sellsRepository): Response
    {
      
    //   $consumptionRequest =$consumptionRequestRepository->findBy('approvalStatus',null);
      $consumptionRequest = $consumptionRequestRepository->findBy(['approvalStatus' => 1]);
      $totalConsumption = count($consumptionRequest);
      
      $stockRequest = $stockRequestRepository->findBy(['approvalStatus' => 1]);
      $totalStock = count($stockRequest);

    //   $sells = $sellsRepository->findBy(['approvalStatus' => 1]);
      $totalGoods = count($stockRequest);

    //   $students = $this->getDoctrine() 
    //                    ->getRepository('AppBundle:Department') 
    //                    ->findAll();

        if ($request->isXmlHttpRequest() || $request->query->get('showJson') == 1) {  
            $jsonData = array(
                'consumption' => $totalConsumption,
                'stock' =>  $totalStock,
                'goods' => $totalGoods
            );  
            
            return new JsonResponse($jsonData); 
        }

        if($request->request->get('some_var_name')){
            //make something curious, get some unbelieveable data
            $arrData = ['output' => 'here the result which will appear in div'];
            return new JsonResponse($arrData);
        }

        return $this->render('notification/index.html.twig', [
            'controller_name' => 'NotificationController',
            'consum'=>$consumptionRequest,
            'totalconsumption' => $totalConsumption,
            'totalstock' => $totalStock,
            'totalgood' => $totalGoods
        ]);
    }
} 