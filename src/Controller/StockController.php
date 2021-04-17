<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\StockApproval;
use App\Entity\StockList;
use App\Form\StockType;
use App\Form\StockListType;
use App\Form\StockApprovalType;
use App\Repository\StockRepository;
use App\Repository\StockListRepository;
use App\Repository\SettingRepository;
use App\Repository\StockApprovalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
// use Troopers\AlertifyBundle\Helper\AlertifyHelper;
/**
 * @Route("/stock")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="stock_index", methods={"GET","POST"})
     */
    public function index(StockRepository $stockRepository, StockListRepository $stockListRepository, SettingRepository $settingRepository, Request $request, PaginatorInterface $paginator): Response
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
            $stock = $stockRepository->find($id);
            $count = 0;
            foreach($stock->getStockLists() as $list){
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
                    $count = $count+1;
                }
             }
             
            } 
            $user = $this->getUser();
            if ($count == count($stock->getStockLists())){
                $stock->setApprovedBy($user)
                //   ->setNote($note)
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Stock has been rejected!');
            }else{
                $stock->setApprovedBy($user)
                //   ->setNote($note)
                  ->setApprovalStatus(1);
            $this->addFlash('save', 'The Stock has been approved!');
            }
            
        }
        elseif($request->request->get('reject')){
            $user = $this->getUser();
            $id = $request->request->get('reject');
            $stock = $stockRepository->find($id);
            $stockList = $stock->getStockLists();
            foreach($stockList as $list){

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
            $stock->setApprovedBy($user)
                //   ->setNote($request->request->get('remark'))
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Stock Delivery has been  Rejected!');
        }

        

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
        // $queryBuilder=$sellsRepository->findAll();
        // $data=$paginator->paginate(
        //     $queryBuilder,
        //     $request->query->getInt('page',1),
        //     10
        // );
      
        $queryBuilder=$stockRepository->findStock($request->query->get('search'));
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );
        
        $qb = $stockListRepository->findAll();
        return $this->render('stock/index.html.twig', [
            'stocks' => $data,
            // 'edit_list'=>$editlist,
            'stocklist'=>$qb,
            'edit'=>false,

        ]);
    }
    
    /**
     * @Route("/new", name="new_stock_index", methods={"GET","POST"})
     */
    public function newStock(StockApprovalRepository $stockApprovalRepository,settingRepository $settingRepository, StockRepository $stockRepository, StockListRepository $stockListRepository, Request $request): Response
    {  

    
    $entityManager = $this->getDoctrine()->getManager();
       
        $stock = new Stock();
        $form_stock = $this->createForm(StockType::class, $stock);
        $form_stock->handleRequest($request);
        $user = $this->getUser();
        $stock->setRegisteredBy($user)
              ->setDatePurchased(new \DateTime());

        if ($form_stock->isSubmitted() && $form_stock->isValid()) {    
               
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($stock);
            $entityManager->flush();
            $this->addFlash("save",'New Stock Delivery Added');

            $stockId = $stock->getId();
            return $this->redirectToRoute('edit_stock_index',['id'=>$stockId]);
               
        }

        if($stock){
            $qb=$stockListRepository->findBy(['stock'=>$stock]);
        }else{
            $qb=null;
        }

        return $this->render('stock/stock_form.html.twig', [
            'stock_list' => $qb,
            'sells_lists'=>$stock->getId(),
            'add_item'=>false,
            'form_stock'=> $form_stock->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$stock->getId(),
            
        ]);
       }
     
    /**
     * @Route("/editstock/{id}", name="edit_stock_index", methods={"GET","POST"})
     */
    public function EditStock(StockListRepository $stockListRepository,settingRepository $settingRepository, Request $request, StockRepository $stockRepository,$id ): Response
    {  
        
        $entityManager = $this->getDoctrine()->getManager();
        if($request->request->get('edit')){
            $stockId=$request->request->get('edit');
            $stock = $stockRepository->find($stockId);
        }elseif($request->request->get("parentId")){
            
            $stock = $entityManager->getRepository(Stock::class)->find($request->request->get("parentId"));
        }
        else{
            $stock = $stockRepository->find($id);
        }
        $form_stock = $this->createForm(StockType::class, $stock);
        $form_stock->handleRequest($request);

        $List = new StockList();
        $form_stock_list = $this->createForm(StockListType::class,$List);
        $form_stock_list->handleRequest($request);
        
        // edit info on stocks
        if ($form_stock->isSubmitted() && $form_stock->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Stock Updated');
            return $this->redirectToRoute('edit_stock_index',['id'=>$stockId]);
            
        }

        // add new item on the sell to sellsList
        if ($form_stock_list->isSubmitted() && $form_stock_list->isValid()) {
            
            $stock = $entityManager->getRepository(Stock::class)->find($request->request->get("parentId"));
            $List->setStock($stock);
            $entityManager->persist($List);
            $entityManager->flush(); 
            $this->addFlash("save",'Item Added');
            return $this->redirectToRoute('edit_stock_index',['id'=>$stock->getId()]);
            
        }
        
        $qb=$stockListRepository->findBy(['stock'=>$stock]);
        return $this->render('stock/stock_form.html.twig', [
            'stock_list' => $qb,
            'form_stock' => $form_stock->createView(),
            'form_stock_list' => $form_stock_list->createView(),
            'add_item'=>true,
            'edit'=>$stock->getId(),
            'edit_list'=>false,
            'stock_lists'=>$List,
            'id'=>$stock->getId(),
        ]);
    

       }
    
    /**
     * @Route("/{id}", name="stock_delete", methods={"DELETE"})
     */
    public function delete(Request $request, stock $stock): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/stockitem/{id}", name="stock_list_delete", methods={"DELETE"})
     */
    public function deleteList(Request $request, StockList $stock): Response
    {
        
        if ($this->isCsrfTokenValid('delete'.$stock->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stock);
            $entityManager->flush();
        }
        
        return $this->redirect($request->headers->get('referer'));
    }

     /**
     * @Route("/download/{id}", name="download_stock_index", methods={"POST","GET"})
     */
    public function downloadList(StockListRepository $stockListRepository, Request $request, StockRepository $stockRepository, $id): Response
    {

        $data = $stockRepository->find($id);
        // $sellsList = new SellsList();
        $qb = $stockListRepository->findBy(['stock' => $id]);
        // $theDate = new \DateTime();
        $theDate = date_create();
        $date = $theDate->format('Y-m-d');
        return $this->render('stock/2.html.twig', [
            'stock' => $data,
            'stock_list'=>$qb,
            'date' => $date,
        ]);
    }
}
