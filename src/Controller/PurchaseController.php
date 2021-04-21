<?php

namespace App\Controller;

use App\Entity\StockRequest;
use App\Entity\StockRequestList;
use App\Entity\StockList;
use App\Form\PurchaseType;
use App\Form\PurchaseListType;

use App\Form\StockApprovalType;
use App\Repository\StockRequestRepository;
use App\Repository\StockRequestListRepository;

use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Troopers\AlertifyBundle\Helper\AlertifyHelper;
/**
 * @Route("/purchase")
 */
class PurchaseController extends AbstractController
{
    /**
     * @Route("/", name="purchase_index", methods={"GET","POST"})
     */
    public function index(StockRequestRepository $stockRequestRepository,SettingRepository $settingRepository, Request $request, PaginatorInterface $paginator, StockRequestListRepository $stockRequestListRepository): Response
    {
        $stockApprovalLevel = $settingRepository->findOneBy(['code'=>'stock_approval_level'])->getValue();
        // dd($request->request->all());
        // $stockRequestList = $stockRequestListRepository->find($request->request->get('child_id'));
        // $form_stockRequest_list = $this->createForm(StockRequestListType::class, $stockRequestList);
        // $form_stockRequest_list->handleRequest($request);
        // if ($form_stockRequest_list->isSubmitted() && $form_stockRequest_list->isValid()) {
        //     $this->getDoctrine()->getManager()->flush();
        //     $this->addFlash("save",'saved');
        //     // return $this->redirectToRoute('goods_delivery_index');
        // }

        
        if($request->request->get('approve')){

            // dd($request->request->all());
            $note = $request->request->get('remark');
            $id = $request->request->get('approve');
            $stockRequest = $stockRequestRepository->find($id);
            
            foreach($stockRequest->getStockRequestLists() as $list){
                $listId = $list->getId();
                $var = $request->request->get("quantity$listId");
                if($var > $list->getQuantity()){
                    $this->addFlash('error', 'please make sure the approved quantity is less than the quantity!');
                    return $this->redirectToRoute('purchase_index');
                }else{
                if($request->request->get("quantity$listId") and $request->request->get("mySelect$listId") == "Approve some"){
                    $list->setApprovedQuantity($request->request->get("quantity$listId"))
                         ->setRemark($request->request->get("remark$listId"))
                         ->setApprovalStatus(1);
                }
                // if($request->request->get("remark$listId")){
                //     $list->setRemark($request->request->get("remark$listId"));
                // }
                if($request->request->get("mySelect$listId") == "Approve all"){
                    $list->setApprovedQuantity($list->getQuantity())
                         ->setApprovalStatus(1);
                }
                if($request->request->get("mySelect$listId") == "Reject" and $request->request->get("remark$listId")){
                    $list->setApprovalStatus(2)
                         ->setRemark($request->request->get("remark$listId"));
                }
             }
            } 
            $user = $this->getUser();
            $stockRequest->setApprovedBy($user)
                  ->setNote($note)
                  ->setApprovalStatus(1);
            $this->addFlash('save', 'The  Request has been approved!');
        }
        elseif($request->request->get('reject')){
            $user = $this->getUser();
            $id = $request->request->get('reject');
            $stockRequest = $stockRequestRepository->find($id);
            $stockRequestList = $stockRequest->getStockRequestLists();
            foreach($stockRequestList as $list){

                $listId = $list->getId();
                $list->setApprovalStatus(2);
                // if($request->request->get("quantity.$listId")){
                //     $list->setApprovedQuantity($request->request->get("quantity.$listId"));
                // }
                // if($request->request->get("remark.$listId")){
                //     $list->setRemark($request->request->get("remark.$listId"));
                // }
                // if($request->request->get("mySelect$listId") == "Approve all"){
                //     $list->setApprovedQuantity($list->getQuantity());
                // }
                
            }
            $stockRequest->setApprovedBy($user)
                  ->setNote($request->request->get('remark'))
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Stock Request has been  Rejected!');
        }

        

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
        // $queryBuilder=$purchaseRepository->findAll();
        // $data=$paginator->paginate(
        //     $queryBuilder,
        //     $request->query->getInt('page',1),
        //     10
        // );
      
        $queryBuilder=$stockRequestRepository->findAll();
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );
        
        $qb = $stockRequestListRepository->findAll();
        return $this->render('purchase/index.html.twig', [
            'stockRequest' => $data,
            // 'edit_list'=>$editlist,
            'stockRequestlist'=>$qb,
            'edit'=>false,

        ]);
    }
    


    /**
     * @Route("/new", name="new_purchase_index", methods={"GET","POST"})
     */
    public function NewPurchase(StockRequestListRepository $stockRequestListRepository,settingRepository $settingRepository, Request $request, StockRequestRepository $stockRequestRepository ): Response
    {  
        
        $entityManager = $this->getDoctrine()->getManager();
       
        $stockRequest = new StockRequest();
        $form_stockRequest = $this->createForm(PurchaseType::class, $stockRequest);
        $form_stockRequest ->handleRequest($request);
        $user = $this->getUser();
        $stockRequest ->setRequestedBy($user)
                      ->setDateOfRequest(new \DateTime())
                      ->setApprovalStatus(3);

        if ($form_stockRequest ->isSubmitted() && $form_stockRequest ->isValid()) {    
               
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($stockRequest );
            $entityManager->flush();
            $this->addFlash("save",'New Request Added');

            $stockRequestId = $stockRequest->getId();
            return $this->redirectToRoute('edit_stockRequest_index',['id'=>$stockRequestId]);
               
        }

        if($stockRequest){
            $qb=$stockRequestListRepository->findBy(['stockRequest'=>$stockRequest]);
        }else{
            $qb=null;
        }

        return $this->render('purchase/purchase_form.html.twig', [
            'stockRequest_list' => $qb,
            'stockRequest_lists'=>$stockRequest->getId(),
            'add_item'=>false,
            'form_stockRequest'=> $form_stockRequest->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$stockRequest->getId(),
            
        ]);
       }

    /**
     * @Route("/editstockRequest/{id}", name="edit_stockRequest_index", methods={"GET","POST"})
     */
    public function EditStockRequest(StockRequestListRepository $stockRequestListRepository,settingRepository $settingRepository, Request $request, StockRequestRepository $stockRequestRepository,$id ): Response
    {  
        
        $entityManager = $this->getDoctrine()->getManager();
        if($request->request->get('edit')){
            $stockRequestId=$request->request->get('edit');
            $stockRequest = $stockRequestRepository->find($stockRequestId);
        }elseif($request->request->get("parentId")){
            
            $stockRequest = $entityManager->getRepository(StockRequest::class)->find($request->request->get("parentId"));
        }
        else{
            $stockRequest = $stockRequestRepository->find($id);
        }
        $form_stockRequest = $this->createForm(PurchaseType::class, $stockRequest);
        $form_stockRequest->handleRequest($request);

        $stockRequestList = new StockRequestList();
        $form_stockRequest_list = $this->createForm(PurchaseListType::class,$stockRequestList);
        $form_stockRequest_list->handleRequest($request);
        
        // edit info on stockRequest
        if ($form_stockRequest->isSubmitted() && $form_stockRequest->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Request Updated');
            return $this->redirectToRoute('edit_purchase_index',['id'=>$stockRequestId]);
            
        }

        // add new item on the stockRequest to stockRequestList
        if ($form_stockRequest_list->isSubmitted() && $form_stockRequest_list->isValid()) {
            
            $stockRequest = $entityManager->getRepository(StockRequest::class)->find($request->request->get("parentId"));
            $stockRequestList->setStockRequest($stockRequest);
            $entityManager->persist($stockRequestList);
            $entityManager->flush(); 
            $this->addFlash("save",'Item Added');
            return $this->redirectToRoute('edit_stockRequest_index',['id'=>$stockRequest->getId()]);
            
        }
        
        $qb=$stockRequestListRepository->findBy(['stockRequest'=>$stockRequest]);
        return $this->render('purchase/purchase_form.html.twig', [
            'stockRequest_list' => $qb,
            'form_stockRequest' => $form_stockRequest->createView(),
            'form_stockRequest_list' => $form_stockRequest_list->createView(),
            'add_item'=>true,
            'edit'=>$stockRequest->getId(),
            'edit_list'=>false,
            'stockRequest_lists'=>$stockRequestList,
            'id'=>$stockRequest->getId(),
        ]);
    

       }
    /**
     * @Route("/editlist/{id}", name="edit_purchase_list_index", methods={"GET","POST"})
     */
    public function EditStockRequestList(StockRequestListRepository $stockRequestListRepository,settingRepository $settingRepository, Request $request, StockRequestRepository $stockRequestRepository,$id ): Response
    {  
      
      
        $stockRequestList = $stockRequestListRepository->find($id);

        $stockRequest = $stockRequestList->getStockRequest();
        $form_stockRequest_list = $this->createForm(PurchaseListType::class, $stockRequestList);
        $form_stockRequest_list->handleRequest($request);

        $form_stockRequest = $this->createForm(PurchaseType::class,$stockRequest);
        $form_stockRequest->handleRequest($request);

        if ($form_stockRequest_list->isSubmitted() && $form_stockRequest_list->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Item Updated');
            return $this->redirect($request->headers->get('referer'));
        }

        $qb=$stockRequestListRepository->findBy(['stockRequest'=>$stockRequest]);
        return $this->render('purchase/purchase_form.html.twig', [
            'stockRequest_list' => $qb,
            'form_stockRequest' => $form_stockRequest->createView(),
            'form_stockRequest_list' => $form_stockRequest_list->createView(),
            'add_item'=>true,
            'edit'=>false,
            'edit_list'=>true,
            'stockRequest_lists'=>$stockRequestList,
            'id'=>$stockRequest->getId(),  
        ]);
        

       }
    
    /**
     * @Route("/{id}", name="purchase_delete", methods={"DELETE"})
     */
    public function delete(Request $request, StockRequest $stockRequest): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockRequest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stockRequest);
            $entityManager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Route("/child/{id}", name="purchase_list_delete", methods={"DELETE"})
     */
    public function deleteList(Request $request, StockRequestList $stockRequest): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$stockRequest->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stockRequest);
            $entityManager->flush();
        }
        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/download/{id}", name="download_purchase_index", methods={"GET","POST"})
     */

    public function downloadList(StockRequestListRepository $stockRequestListRepository,settingRepository $settingRepository, Request $request, StockRequestRepository $stockRequestRepository,$id)
    {
    //  $id = $request->request->get('edit');
     $data = $stockRequestRepository->find($id);
     $qb = $stockRequestListRepository->findBy(['stockRequest'=> $id]);
     $theDate = new \DateTime();
     $date = $theDate->format('Y-m-d H:i:s');
     return $this->render('purchase/1.html.twig', [
       'stockRequest'=>$data,
       'stockRequestList'=>$qb,
       'date'=>$date,
     ]);
    }
}
