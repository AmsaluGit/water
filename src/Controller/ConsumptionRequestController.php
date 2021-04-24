<?php

namespace App\Controller;

use App\Entity\ConsumptionRequest;
use App\Entity\ConsumptionRequestList;
use App\Entity\ConsumptionDelivery;
use App\Entity\ConsumptionDeliveryList;
use App\Form\ConsumptionRequestType;
use App\Form\ConsumptionRequestListType;
use App\Repository\ConsumptionRequestRepository;
use App\Repository\ConsumptionRequestListRepository;
use App\Repository\StockBalanceRepository;
use App\Repository\StockListRepository;
use App\Repository\SettingRepository;
use App\Form\ConsumptionApprovalForm;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/consumption")
 */
class ConsumptionRequestController extends AbstractController
{
    /**
     * @Route("/", name="consumption_index", methods={"GET","POST"})
     */
    public function index(StockBalanceRepository $stockBalanceRepository, ConsumptionRequestListRepository $consumptionRequestListRepository, ConsumptionRequestRepository $consumptionRequestRepository, StockListRepository $stockListRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted("consumption_request_list");
        if($request->request->get('approve')){
            $this->denyAccessUnlessGranted("consumption_request_approval");
            $note = $request->request->get('remark');
            $id = $request->request->get('approve');
            $consumptionRequest =$consumptionRequestRepository->find($id);

            $consumptionDelivery = new ConsumptionDelivery();        //new
            $consumptionDelivery->setRequestNo($consumptionRequest)
                                ->setApprovalStatus(3);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consumptionDelivery);
            // $entityManager->flush();
            // $availableInStock = true;
            $serial= $consumptionRequestRepository->getMaxSerialNo();
            $serial_num = 0;
            if($serial){
                $consumptionRequest->setSerialNo(($serial[0]->getSerialNo() + 1));
            }
            else{
                $consumptionRequest->setSerialNo($serial_num);

            }
            foreach($consumptionRequest->getConsumptionRequestLists() as $list){
                $listId = $list->getId();
                $var = $request->request->get("quantity$listId");

                if($var > $list->getQuantity() || $var > $list->getAvailable()){
                    $this->addFlash('error', 'please make sure the approved quantity is less than the quantity!');
                    return $this->redirectToRoute('consumption_index');
                }
                else{
                    if($request->request->get("quantity$listId") and $request->get("mySelect$listId") == "Approve some"){
                        $list->setApprovedQuantity($request->request->get("quantity$listId"))
                             ->setRemark($request->request->get("remark$listId"))
                             ->setApprovalStatus(1);

                        $consumptionDeliveryList =new ConsumptionDeliveryList();
                        $consumptionDeliveryList->setProduct($list->getProduct())
                                                ->setUnitOfMeasure($list->getUnitOfMeasure())
                                                ->setCodeNumber($list->getCodeNumber())
                                                ->setQuantity($request->request->get("quantity$listId"))
                                                ->setUnitPrice($request->request->get("unit$listId"));
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($consumptionDeliveryList);
                        $consumptionDelivery->addConsumptionDeliveryList($consumptionDeliveryList);
                    }

                    if($request->request->get("mySelect$listId") == "Approve all"){
                        if($list->getQuantity() > $list->getAvailable()){
                            $this->addFlash('error', 'Not Enough in stock');
                            return $this->redirectToRoute('consumption_index');
                        }else{

                            $list->setApprovedQuantity($list->getQuantity())
                             ->setApprovalStatus(1);

                        $consumptionDeliveryList =new ConsumptionDeliveryList();
                        $consumptionDeliveryList->setProduct($list->getProduct())
                                                ->setUnitOfMeasure($list->getUnitOfMeasure())
                                                ->setCodeNumber($list->getCodeNumber())
                                                ->setQuantity($list->getQuantity())
                                                ->setUnitPrice($request->request->get("unit$listId"));
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($consumptionDeliveryList);
                        $consumptionDelivery->addConsumptionDeliveryList($consumptionDeliveryList);

                        }
                        
                    }
                    if($request->request->get("mySelect$listId") == "Reject" and $request->request->get("remark$listId")){
                        $list->setApprovalStatus(2)
                             ->setRemark($request->request->get("remark$listId"));
                    }

                }
            }
            
                $user = $this->getUser();
                $consumptionRequest->setApprovedBy($user)
                                   ->setNote($note)
                                   ->setApprovalStatus(1);

            $this->addFlash('save', 'The Consumption request has been approved!');
            
            

        }
        elseif ($request->request->get("reject")){
            $this->denyAccessUnlessGranted("consumption_request_approval");
            $user = $this->getUser();
            $id = $request->request->get('reject');
            $consumptionRequest = $consumptionRequestRepository->find($id);
            $consumptionRequestList = $consumptionRequest->getConsumptionRequestLists();

            foreach($consumptionRequestList as $list){
                $listId =$list->getId();
                $list->setApprovalStatus(2);
            }

            $consumptionRequest->setApprovedBy($user)
                               ->setNote($request->request->get('remark'))
                               ->setApprovalStatus(2);

            $this->addFlash('save', 'The Consumption request has been  Rejected!');
        }

        $dp = $consumptionRequestListRepository->findAll();

        foreach($dp as $ls){
            $tot = 0;
            if($ls->getConsumptionRequest()->getApprovalStatus()== 3){
            $product=$ls->getProduct();
            $avail = $stockBalanceRepository->findBy(["product" => $product])[0]->getAvailable();
            
            // foreach($avail as $av){
            //     if($av->getApprovalStatus()==1 ){
            //         $tot = $tot + $av->getApprovedQuantity();
            //     }            
            // }
            
            $ls->setAvailable($avail);
        }
                
        
          }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

          
        $queryBuilder=$consumptionRequestRepository->findRequester($request->query->get('search'));
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );

            // $dp = $consumptionRequestListRepository->findAll();

            // Available
            
            // foreach($dp as $ls){
            //     $product=$ls->getProduct();
            //     $avail = $stockListRepository->findBy(["product" => $product]);
            //     $tot = 0;
            //     if($ls->getConsumptionRequest()->getApprovalStatus() == 3){
            //         // foreach($avail as $av){
            //         //     if($av->getApprovalStatus() == 1){
            //         //         $tot = $tot + $av->getApprovedQuantity();
            //         //     }
                         
            //         // }
            //         $tot = 
            //         $ls->setAvailable($tot);
            //     }
                   

            // }

            //   $entityManager = $this->getDoctrine()->getManager();
            //   $entityManager->flush();
            return $this->render('consumption_request/index.html.twig', [
                'consumption_requests' => $data,
                'consumption_list' => $dp,
                'edit' =>false
            ]);
    }
    /**
     * @Route("/new", name="new_consumption_request_index", methods={"GET","POST"})
     */
    public function newConsumptionRequest(ConsumptionRequestListRepository $consumptionRequestListRepository, Request $request, ConsumptionRequestRepository $consumptionRequestRepository): Response
    {
        $this->denyAccessUnlessGranted("consumption_request_new");
        $entityManager = $this->getDoctrine()->getManager();

        $consumptionRequest = new ConsumptionRequest();
        $form_consumption = $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
        $form_consumption->handleRequest($request);

        $user = $this->getUser();
        $consumptionRequest->setRequester($user)
                           ->setApprovalStatus(3);

        if($form_consumption->isSubmitted() && $form_consumption->isValid()){
            $consumptionRequest->setRequestedDate(new \DateTime());//here
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consumptionRequest);
            $entityManager->flush();
            $this->addFlash("save",'New Consumption Request Added');

            $consumptionRequestId = $consumptionRequest->getId();
            return $this->redirectToRoute('edit_consumption_request_index',['id'=>$consumptionRequestId]);
        }

        if($consumptionRequest){
            $qb = $consumptionRequestListRepository->findBy(['consumptionRequest'=>$consumptionRequest]);
        }
        else{
            $qb = null;
        }

        return $this->render('consumption_request/newRequest_form.html.twig', [
            'consumption_list' => $qb,
            'consumption_lists'=>$consumptionRequest->getId(),
            'add_item'=>false,
            'form_consumption'=> $form_consumption->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$consumptionRequest->getId(),

        ]);
    }
    /**
     * @Route("/editconsumption/{id}", name="edit_consumption_request_index", methods={"GET","POST"})
     */

     public function editConsumptionRequest(StockBalanceRepository $stockBalanceRepository, ConsumptionRequestListRepository $consumptionRequestListRepository, Request $request, ConsumptionRequestRepository $consumptionRequestRepository, $id): Response
     {
         $this->denyAccessUnlessGranted("consumption_request_edit");
         $entityManager = $this->getDoctrine()->getManager();

         if($request->request->get('edit')){
             $consumptionRequestId = $request->request->get('edit');
             $consumptionRequest = $consumptionRequestRepository->find($consumptionRequestId);
         }
         elseif($request->request->get('parentId')){
             $consumptionRequest =$entityManager->getRepository(ConsumptionRequest::class)->find($request->request->get("parentId"));
         }
         else{
             $consumptionRequest = $consumptionRequestRepository->find($id);
         }

          $form_consumption= $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
          $form_consumption->handleRequest($request);

          $consumptionRequestList = new ConsumptionRequestList();
          $form_consumption_list = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
          $form_consumption_list->handleRequest($request);
          

          //edit info on Consumption Request
          if($form_consumption->isSubmitted() && $form_consumption->isValid()){

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Consumptin Request Updated');
            return $this->redirectToRoute('edit_consumption_request_index',['id'=>$consumptionRequestId]);
          }

          //add new item on the ConsumptionRequest to ConsumptionRequestList
          if($form_consumption_list->isSubmitted() && $form_consumption_list->isValid()){
              $consumptionRequest = $entityManager->getRepository(ConsumptionRequest::class)->find($request->request->get("parentId"));
              $consumptionRequestList->setConsumptionRequest($consumptionRequest);
              $consumptionRequestList->setAvailable($stockBalanceRepository->findBy(["product"=>$consumptionRequestList->getProduct()])[0]->getAvailable());

          // $consumptionRequestList->setIssue(4);
              $entityManager->persist($consumptionRequestList);
              $entityManager->flush();
              $this->addFlash("save",'ConsumptionList Added');
              return $this->redirectToRoute('edit_consumption_request_index',['id'=>$consumptionRequest->getId()]);
          }


          $qb = $consumptionRequestListRepository->findBy(['consumptionRequest'=>$consumptionRequest]);
          return $this->render('consumption_request/newRequest_form.html.twig', [
            'consumption_list' => $qb,
            'form_consumption' => $form_consumption->createView(),
            'form_consumption_list' => $form_consumption_list->createView(),
            'add_item'=>true,
            'edit'=>$consumptionRequest->getId(),
            'edit_list'=>false,
            'consumption_lists'=>$consumptionRequestList,
            'id'=>$consumptionRequest->getId(),
        ]);

    }
    /**
     * @Route("/editconsumptionlist/{id}", name="edit_consumption_request_list_index", methods={"GET","POST"})
     */
    public function editConsumptionRequestList(ConsumptionRequestListRepository $consumptionRequestListRepository, Request $request, ConsumptionRequestRepository $consumptionRequestRepository,$id ): Response
    {
        $this->denyAccessUnlessGranted("consumption_request_edit");

        $consumptionRequestList = $consumptionRequestListRepository->find($id);

        $consumptionRequest = $consumptionRequestList->getConsumptionRequest();
        $form_consumption_list = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
        $form_consumption_list->handleRequest($request);

        $form_consumption = $this->createForm(ConsumptionRequestType::class,$consumptionRequest);
        $form_consumption->handleRequest($request);

        if ($form_consumption_list->isSubmitted() && $form_consumption_list->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Item Updated');
            return $this->redirectToRoute('edit_consumption_request_index',['id'=>$consumptionRequest->getId()]);
        }

        $qb=$consumptionRequestListRepository->findBy(['consumptionRequest'=>$consumptionRequest]);
        return $this->render('consumption_request/newRequest_form.html.twig', [
            'consumption_list' => $qb,
            'form_consumption' => $form_consumption->createView(),
            'form_consumption_list' => $form_consumption_list->createView(),
            'add_item'=>true,
            'edit'=>false,
            'edit_list'=>true,
            'consumption_lists'=>$consumptionRequestList,
            'id'=>$consumptionRequest->getId(),
        ]);

    }

      /**
     * @Route("/{id}", name="consumption_request_delete", methods={"DELETE"})
     */
    public function parentDelete(Request $request, ConsumptionRequest $consumptionRequest): Response
    {
        $this->denyAccessUnlessGranted("consumption_request_delete");

        if ($this->isCsrfTokenValid('delete'.$consumptionRequest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($consumptionRequest);
            $entityManager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Route("/child/{id}", name="consumption_request_list_delete", methods={"DELETE"})
     */
    public function deleteChild(Request $request, ConsumptionRequestList $consumptionRequestList): Response
    {
        $this->denyAccessUnlessGranted("consumption_request_delete");

        if ($this->isCsrfTokenValid('delete'.$consumptionRequestList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($consumptionRequestList);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/print{id}", name="print_page_index", methods={"GET", "POST"})
     */
    public function printPage(Request $request, ConsumptionRequestListRepository $consumptionRequestListRepository ,ConsumptionRequestRepository $consumptionRequestRepository, $id): Response
    {
        $consumptionRequestId = $id;
        $consumptionRequest = $consumptionRequestRepository->find($consumptionRequestId);
        $consumptionRequestList=$consumptionRequestListRepository->findBy(['consumptionRequest'=>$consumptionRequest]);
        return $this->render('consumption_request/page3.html.twig', [
           'consumption_request'=>$consumptionRequest,
           'consumption_lists' =>$consumptionRequestList
       ]);
    }



}

