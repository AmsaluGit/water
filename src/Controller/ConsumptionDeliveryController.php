<?php

namespace App\Controller;

use App\Entity\ConsumptionDelivery;
use App\Form\ConsumptionDeliveryType;
use App\Repository\ConsumptionDeliveryRepository;
use App\Entity\ConsumptionDeliveryList;
use App\Form\ConsumptionDeliveryListType;
use App\Repository\ConsumptionDeliveryListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/delivery")
 */
class ConsumptionDeliveryController extends AbstractController
{
    /**
     * @Route("/", name="consumption_delivery_index", methods={"GET","POST"})
     */
    public function index(ConsumptionDeliveryListRepository $consumptionDeliveryListRepository, ConsumptionDeliveryRepository $consumptionDeliveryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if($request->request->get('approve')){
            $note = $request->request->get('remark');
            $id = $request->request->get('approve');
            $consumptionDelivery =$consumptionDeliveryRepository->find($id);

            foreach($consumptionDelivery->getConsumptionDeliveryLists() as $list){
                $listId = $list->getId();
                $var = $request->request->get("quantity$listId");

                if($var > $list->getQuantity()){
                    $this->addFlash('error', 'please make sure the approved quantity is less than the quantity!');
                    return $this->redirectToRoute('consumption_delivery_index');
                }
                else{
                    if($request->request->get("quantity$listId") and $request->get("mySelect$listId") == "Approve some"){
                        $list->setApprovedQuantity($request->request->get("quantity$listId"))
                             ->setRemark($request->request->get("remark$listId"))
                             ->setApprovalStatus(1);
                    }

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
            $consumptionDelivery->setApprovedBy($user)
                                ->setNote($note)
                                ->setApprovalStatus(1);
            
            $this->addFlash('save', 'The Consumption Delivery has been approved!');

        }
        elseif ($request->request->get("reject")){
            $user = $this->getUser();
            $id = $request->request->get('reject');
            $consumptionDelivery = $consumptionDeliveryRepository->find($id);
            $consumptionDeliveryList = $consumptionDelivery->getConsumptionDeliveryLists();

            foreach($consumptionDeliveryList as $list){
                $listId =$list->getId();
                $list->setApprovalStatus(2);
            }

            $consumptionDelivery->setApprovedBy($user)
                               ->setNote($request->request->get('remark'))
                               ->setApprovalStatus(2);
            
            $this->addFlash('save', 'The Consumption request has been  Rejected!');
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // $queryBuilder=$consumptionDeliveryRepository->findDelivery($request->query->get('search'));
        $queryBuilder=$consumptionDeliveryRepository->findAll();

                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );

            $dp = $consumptionDeliveryListRepository->findAll();
            return $this->render('consumption_delivery/index.html.twig', [
                'consumption_deliveries' => $data,
                'consumption_list' => $dp,
                'edit' =>false
            ]);
    }
    /**
     * @Route("/new", name="new_consumption_delivery_index", methods={"GET","POST"})
     */
    public function newConsumptionDelivery(ConsumptionDeliveryListRepository $consumptionDeliveryListRepository, Request $request, ConsumptionDeliveryRepository $consumptionDeliveryRepository): Response
    {
        $entityManager = $this->getDoctrine()->getManager();

        $consumptionDelivery = new ConsumptionDelivery();
        $form_consumption = $this->createForm(ConsumptionDeliveryType::class, $consumptionDelivery);
        $form_consumption->handleRequest($request);

        $user = $this->getUser();
        $consumptionDelivery->setReceiver($user)
                            ->setApprovalStatus(3);

        if($form_consumption->isSubmitted() && $form_consumption->isValid()){
            $consumptionDelivery->setDeliveredDate(new \DateTime());//here
            $consumptionDelivery->setDeliveredBy($user);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($consumptionDelivery);
            $entityManager->flush();
            $this->addFlash("save",'New Consumption Request Added');

            $consumptionDeliveryId = $consumptionDelivery->getId();
            return $this->redirectToRoute('edit_consumption_delivery_index',['id'=>$consumptionDeliveryId]);
        }

        if($consumptionDelivery){
            $qb = $consumptionDeliveryListRepository->findBy(['consumptionDelivery'=>$consumptionDelivery]);
        }
        else{
            $qb = null;
        }

        return $this->render('consumption_delivery/newRequest_form.html.twig', [
            'consumption_list' => $qb,
            'consumption_lists'=>$consumptionDelivery->getId(),
            'add_item'=>false,
            'form_consumption'=> $form_consumption->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$consumptionDelivery->getId(),
            
        ]);
    }
    /**
     * @Route("/editconsumption/{id}", name="edit_consumption_delivery_index", methods={"GET","POST"})
     */

     public function editConsumptionDelivery(ConsumptionDeliveryListRepository $consumptionDeliveryListRepository, Request $request, ConsumptionDeliveryRepository $consumptionDeliveryRepository, $id): Response
     {
         $entityManager = $this->getDoctrine()->getManager();

         if($request->request->get('edit')){
             $consumptionDeliveryId = $request->request->get('edit');
             $consumptionDelivery = $consumptionDeliveryRepository->find($consumptionDeliveryId);
             if($consumptionDelivery->getReceiver() == null ){
                $consumptionDelivery->setDeliveredDate(new \DateTime);
             }
             $consumptionDelivery->setReceiver($this->getUser());
             
                                 
         }
         elseif($request->request->get('parentId')){
             $consumptionDelivery =$entityManager->getRepository(ConsumptionDelivery::class)->find($request->request->get("parentId"));
         }
         else{
             $consumptionDelivery = $consumptionDeliveryRepository->find($id);
         }

          $form_consumption= $this->createForm(ConsumptionDeliveryType::class, $consumptionDelivery);
          $form_consumption->handleRequest($request);

          $consumptionDeliveryList = new ConsumptionDeliveryList();
          $form_consumption_list = $this->createForm(ConsumptionDeliveryListType::class, $consumptionDeliveryList);
          $form_consumption_list->handleRequest($request);


          //edit info on Consumption Request 
          if($form_consumption->isSubmitted() && $form_consumption->isValid()){

            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Consumption Delivery Updated');
            // return $this->redirectToRoute('edit_consumption_Delivery_index',['id'=>$consumptionDelivery->getId()]);
          }

          //add new item on the ConsumptionRequest to ConsumptionRequestList
          if($form_consumption_list->isSubmitted() && $form_consumption_list->isValid()){
              $consumptionDelivery = $entityManager->getRepository(ConsumptionDelivery::class)->find($request->request->get("parentId"));
              $consumptionDeliveryList->setConsumptionDelivery($consumptionDelivery);
          
          // $consumptionRequestList->setIssue(4);
              $entityManager->persist($consumptionDeliveryList);
              $entityManager->flush();
              $this->addFlash("save",'Consumption Delivery List Added');
              return $this->redirectToRoute('edit_consumption_delivery_index',['id'=>$consumptionDelivery->getId()]);
          }

          $qb = $consumptionDeliveryListRepository->findBy(['consumptionDelivery'=>$consumptionDelivery]);
          return $this->render('consumption_delivery/newRequest_form.html.twig', [
            'consumption_list' => $qb,
            'form_consumption' => $form_consumption->createView(),
            'form_consumption_list' => $form_consumption_list->createView(),
            'add_item'=>true,
            'edit'=>$consumptionDelivery->getId(),
            'edit_list'=>false,
            'consumption_lists'=>$consumptionDeliveryList,
            'id'=>$consumptionDelivery->getId(),
        ]);
    
    }
    /**
     * @Route("/editconsumptionlist/{id}", name="edit_consumption_delivery_list_index", methods={"GET","POST"})
     */
    public function editConsumptionDeliveryList(ConsumptionDeliveryListRepository $consumptionDeliveryListRepository, Request $request, ConsumptionDeliveryRepository $consumptionDeliveryRepository,$id ): Response
    {  
      
        $consumptionDeliveryList = $consumptionDeliveryListRepository->find($id);

        $consumptionDelivery = $consumptionDeliveryList->getConsumptionDelivery();
        $form_consumption_list = $this->createForm(ConsumptionDeliveryListType::class, $consumptionDeliveryList);
        $form_consumption_list->handleRequest($request);

        $form_consumption = $this->createForm(ConsumptionDeliveryType::class,$consumptionDelivery);
        $form_consumption->handleRequest($request);

        if ($form_consumption_list->isSubmitted() && $form_consumption_list->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Item Updated');
            return $this->redirectToRoute('edit_consumption_delivery_index',['id'=>$consumptionDelivery->getId()]);

            // return $this->redirectToRoute($request->headers->get('referer'));
        }

        $qb=$consumptionDeliveryListRepository->findBy(['consumptionDelivery'=>$consumptionDelivery]);
        return $this->render('consumption_delivery/newRequest_form.html.twig', [
            'consumption_list' => $qb,
            'form_consumption' => $form_consumption->createView(),
            'form_consumption_list' => $form_consumption_list->createView(),
            'add_item'=>true,
            'edit'=>false,
            'edit_list'=>true,
            'consumption_lists'=>$consumptionDeliveryList,
            'id'=>$consumptionDelivery->getId(),  
        ]);
        
    }

      /**
     * @Route("/{id}", name="consumption_delivery_delete", methods={"DELETE"})
     */
    public function parentDelete(Request $request, ConsumptionDelivery $consumptionDelivery): Response
    {
        if ($this->isCsrfTokenValid('delete'.$consumptionDelivery->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($consumptionDelivery);
            $entityManager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Route("/child/{id}", name="consumption_delivery_list_delete1", methods={"DELETE"})
     */
    public function deleteChild(Request $request, ConsumptionDeliveryList $consumptionDeliveryList): Response
    {   
        if ($this->isCsrfTokenValid('delete'.$consumptionDeliveryList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($consumptionDeliveryList);
            $entityManager->flush();
        }
        
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/print{id}", name="print_page_index1", methods={"GET", "POST"})
     */
    public function printPage(Request $request, ConsumptionDeliveryListRepository $consumptionDeliveryListRepository ,ConsumptionDeliveryRepository $consumptionDeliveryRepository, $id): Response
    {
        $consumptionDeliveryId = $id;
        $consumptionDelivery = $consumptionDeliveryRepository->find($consumptionDeliveryId);

        if($consumptionDelivery->getRequestNo()){
            $dep = $consumptionDelivery->getRequestNo()->getSection()->getDepartment();
        }else{
            $dep = null;
        }

        $consumptionDeliveryList=$consumptionDeliveryListRepository->findBy(['consumptionDelivery'=>$consumptionDelivery]);
        return $this->render('consumption_delivery/page4.html.twig', [
           'consumption_delivery'=>$consumptionDelivery,
           'consumption_lists' =>$consumptionDeliveryList,
           'dep' => $dep
       ]);
    }

}

