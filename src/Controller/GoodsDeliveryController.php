<?php

namespace App\Controller;

use App\Entity\Sells;
use App\Entity\SellsList;
use App\Entity\StockList;
use App\Form\StockType;
use App\Form\SellsType;
use App\Form\SellsListType;
use App\Form\StockListType;
use App\Form\StockApprovalType;
use App\Repository\SellsRepository;
use App\Repository\SettingRepository;
use App\Repository\SellsListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Troopers\AlertifyBundle\Helper\AlertifyHelper;
/**
 * @Route("/sell")
 */
class GoodsDeliveryController extends AbstractController
{
    /**
     * @Route("/", name="goods_delivery_index", methods={"GET","POST"})
     */
    public function index(SellsRepository $sellsRepository,SettingRepository $settingRepository, Request $request, PaginatorInterface $paginator, SellsListRepository $sellsListRepository): Response
    {
        $stockApprovalLevel = $settingRepository->findOneBy(['code'=>'stock_approval_level'])->getValue();
        // dd($request->request->all());
        // $sellsList = $sellsListRepository->find($request->request->get('child_id'));
        // $form_sells_list = $this->createForm(SellsListType::class, $sellsList);
        // $form_sells_list->handleRequest($request);
        // if ($form_sells_list->isSubmitted() && $form_sells_list->isValid()) {
        //     $this->getDoctrine()->getManager()->flush();
        //     $this->addFlash("save",'saved');
        //     // return $this->redirectToRoute('goods_delivery_index');
        // }

        
        if($request->request->get('approve')){

            // dd($request->request->all());
            $note = $request->request->get('remark');
            $id = $request->request->get('approve');
            $sells = $sellsRepository->find($id);
            $count = 0;
            foreach($sells->getSellsLists() as $list){
                $listId = $list->getId();
                $var = $request->request->get("quantity$listId");
                if($var > $list->getQuantity()){
                    $this->addFlash('error', 'please make sure the approved quantity is less than the quantity!');
                    return $this->redirectToRoute('goods_delivery_index');
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
                
                if($request->request->get("mySelect$listId") == "Reject"){
                    $list->setApprovalStatus(2)
                         ->setRemark($request->request->get("remark$listId"));
                    $count = $count + 1;
                }
             }
             
            } 
            $user = $this->getUser();
            if ($count == count($sells->getSellsLists())){
                $sells->setApprovedBy($user)
                  ->setNote($note)
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The sell has been rejected!');
            }else{
                $sells->setApprovedBy($user)
                  ->setNote($note)
                  ->setApprovalStatus(1);
            $this->addFlash('save', 'The Sell has been approved!');
            }
            
        }
        elseif($request->request->get('reject')){
            $user = $this->getUser();
            $id = $request->request->get('reject');
            $sells = $sellsRepository->find($id);
            $sellsList = $sells->getSellsLists();
            foreach($sellsList as $list){

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
            $sells->setApprovedBy($user)
                  ->setNote($request->request->get('remark'))
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Sell has been  Rejected!');
        }

        

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
        // $queryBuilder=$sellsRepository->findAll();
        // $data=$paginator->paginate(
        //     $queryBuilder,
        //     $request->query->getInt('page',1),
        //     10
        // );
      
        $queryBuilder=$sellsRepository->findSells($request->query->get('search'));
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );
        
        $qb = $sellsListRepository->findAll();
        return $this->render('goods_delivery/index.html.twig', [
            'sells' => $data,
            // 'edit_list'=>$editlist,
            'sellslist'=>$qb,
            'edit'=>false,

        ]);
    }
    


    /**
     * @Route("/new", name="new_sell_index", methods={"GET","POST"})
     */
    public function NewSell(SellsListRepository $sellsListRepository,settingRepository $settingRepository, Request $request, SellsRepository $sellsRepository ): Response
    {  
        
        $entityManager = $this->getDoctrine()->getManager();
       
        $sell = new Sells();
        $form_sells = $this->createForm(SellsType::class, $sell);
        $form_sells->handleRequest($request);
        $user = $this->getUser();
        $sell->setDeliveredBy($user);

        if ($form_sells->isSubmitted() && $form_sells->isValid()) {    
               
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($sell);
            $entityManager->flush();
            $this->addFlash("save",'New Delivery Added');

            $sellsId = $sell->getId();
            return $this->redirectToRoute('edit_sells_index',['id'=>$sellsId]);
               
        }

        if($sell){
            $qb=$sellsListRepository->findBy(['sells'=>$sell]);
        }else{
            $qb=null;
        }

        return $this->render('goods_delivery/goods_form.html.twig', [
            'sells_list' => $qb,
            'sells_lists'=>$sell->getId(),
            'add_item'=>false,
            'form_sells'=> $form_sells->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$sell->getId(),
            
        ]);
       }

    /**
     * @Route("/editsells/{id}", name="edit_sells_index", methods={"GET","POST"})
     */
    public function EditSells(SellsListRepository $sellsListRepository,settingRepository $settingRepository, Request $request, SellsRepository $sellsRepository,$id ): Response
    {  
        
        $entityManager = $this->getDoctrine()->getManager();
        if($request->request->get('edit')){
            $sellsId=$request->request->get('edit');
            $sell = $sellsRepository->find($sellsId);
        }elseif($request->request->get("parentId")){
            
            $sell = $entityManager->getRepository(Sells::class)->find($request->request->get("parentId"));
        }
        else{
            $sell = $sellsRepository->find($id);
        }
        $form_sells = $this->createForm(SellsType::class, $sell);
        $form_sells->handleRequest($request);

        $sellsList = new SellsList();
        $form_sells_list = $this->createForm(SellsListType::class,$sellsList);
        $form_sells_list->handleRequest($request);
        
        // edit info on sells
        if ($form_sells->isSubmitted() && $form_sells->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Sell Updated');
            return $this->redirectToRoute('edit_sells_index',['id'=>$sellsId]);
            
        }

        // add new item on the sell to sellsList
        if ($form_sells_list->isSubmitted() && $form_sells_list->isValid()) {
            
            $sell = $entityManager->getRepository(Sells::class)->find($request->request->get("parentId"));
            $sellsList->setSells($sell);
            $entityManager->persist($sellsList);
            $entityManager->flush(); 
            $this->addFlash("save",'Item Added');
            return $this->redirectToRoute('edit_sells_index',['id'=>$sell->getId()]);
            
        }
        
        $qb=$sellsListRepository->findBy(['sells'=>$sell]);
        return $this->render('goods_delivery/goods_form.html.twig', [
            'sells_list' => $qb,
            'form_sells' => $form_sells->createView(),
            'form_sells_list' => $form_sells_list->createView(),
            'add_item'=>true,
            'edit'=>$sell->getId(),
            'edit_list'=>false,
            'sells_lists'=>$sellsList,
            'id'=>$sell->getId(),
        ]);
    

       }
    /**
     * @Route("/editlist/{id}", name="edit_sells_list_index", methods={"GET","POST"})
     */
    public function EditSellsList(SellsListRepository $sellsListRepository,settingRepository $settingRepository, Request $request, SellsRepository $sellsRepository,$id ): Response
    {  
      
      
        $sellsList = $sellsListRepository->find($id);

        $sell = $sellsList->getSells();
        $form_sells_list = $this->createForm(SellsListType::class, $sellsList);
        $form_sells_list->handleRequest($request);

        $form_sells = $this->createForm(SellsType::class,$sell);
        $form_sells->handleRequest($request);

        if ($form_sells_list->isSubmitted() && $form_sells_list->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Item Updated');
            // return $this->redirect($request->headers->get('referer'));
            return $this->redirectToRoute('edit_sells_index',['id'=>$sell->getId()]);
        }

        $qb=$sellsListRepository->findBy(['sells'=>$sell]);
        return $this->render('goods_delivery/goods_form.html.twig', [
            'sells_list' => $qb,
            'form_sells' => $form_sells->createView(),
            'form_sells_list' => $form_sells_list->createView(),
            'add_item'=>true,
            'edit'=>false,
            'edit_list'=>true,
            'sells_lists'=>$sellsList,
            'id'=>$sell->getId(),  
        ]);
        

       }
    
    /**
     * @Route("/{id}", name="goods_delivery_delete", methods={"DELETE"})
     */
    public function delete(Request $request, sells $sells): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sells->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sells);
            $entityManager->flush();
        }
        return $this->redirect($request->headers->get('referer'));
    }
    /**
     * @Route("/child/{id}", name="sells_list_delete", methods={"DELETE"})
     */
    public function deleteList(Request $request, SellsList $sells): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$sells->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sells);
            $entityManager->flush();
        }
        
        return $this->redirect($request->headers->get('referer'));
    }
     /**
     * @Route("/download/{id}", name="download_goods_delivery_index", methods={"POST","GET"})
     */
    public function downloadList(SellsListRepository $sellsListRepository, Request $request, SellsRepository $sellsRepository, $id): Response
    {

        $data = $sellsRepository->find($id);
        // $sellsList = new SellsList();
        $qb = $sellsListRepository->findBy(['sells' => $id]);
        $theDate = new \DateTime();
        $date = $theDate->format('Y-m-d H:i:s');
        return $this->render('goods_delivery/6.html.twig', [
            'sell' => $data,
            'sellslist'=>$qb,
            'date' => $date,
        ]);
    }
}
