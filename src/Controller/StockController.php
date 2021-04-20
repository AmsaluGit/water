<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\StockApproval;
use App\Entity\StockList;
use App\Form\StockType;
use App\Form\StockListType;
use App\Form\StockApprovalType;
use App\Repository\StockRepository;
use App\Repository\SettingRepository;
use App\Repository\StockApprovalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Troopers\AlertifyBundle\Helper\AlertifyHelper;
/**
 * @Route("/stock")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="stock_index", methods={"GET","POST"})
     */
    public function index(StockRepository $stockRepository,SettingRepository $settingRepository, Request $request, PaginatorInterface $paginator): Response
    {
<<<<<<< HEAD
    
        $stockApprovalLevel = $settingRepository->findOneBy(['code'=>'stock_approval_level'])->getValue();
=======
        $stockApprovalLevel = $settingRepository->findOneBy(['code'=>'stock_approval_level'])->getValue();
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $stock=$stockRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(StockListType::class, $stock);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
                $this->addFlash("save",'saved');
                return $this->redirectToRoute('stock_index');
            }

            $queryBuilder=$stockRepository->findstock($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('stock/index.html.twig', [
                'stocks' => $data,
                'form' => $form->createView(),
                'edit'=>$id,
                'applevel'=>$stockApprovalLevel
            ]);

        }

        $stock = new StockList();
        $form = $this->createForm(StockListType::class, $stock);
        $form->handleRequest($request);
        /*$stock ->setDatePurchased(new \DateTime());
        $stock->setRegisteredBy($this->getUser());*/
>>>>>>> ABI
        

<<<<<<< HEAD
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
                   ->setNote($note)
                  ->setApprovalStatus(1);
            $this->addFlash('save', 'The Stock has been approved!');
            }
=======
        if ($form->isSubmitted() && $form->isValid()) {
>>>>>>> ABI
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stock);
            $entityManager->flush();

<<<<<<< HEAD
                $listId = $list->getId();
                $list->setApprovalStatus(2);
                
                
            }
            $stock->setApprovedBy($user)
                //   ->setNote($request->request->get('remark'))
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Stock Delivery has been  Rejected!');
=======
            return $this->redirectToRoute('stock_index');
>>>>>>> ABI
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$stockRepository->findStock($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );

<<<<<<< HEAD
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
      
        $queryBuilder=$stockRepository->findStock($request->query->get('search'));
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );
        
        $qb = $stockListRepository->findAll();
=======
>>>>>>> ABI
        return $this->render('stock/index.html.twig', [
            'stocks' => $data,
            'form' => $form->createView(),
            'edit'=>false,
            'applevel'=>$stockApprovalLevel
        ]);
    }
    
    /**
     * @Route("/{id}", name="product_detail_index", methods={"GET","POST"})
     */
    public function approveStock(StockApprovalRepository $stockApprovalRepository,settingRepository $settingRepository, StockRepository $stockRepository, Request $request, AlertifyHelper $alertify): Response
    {  

<<<<<<< HEAD
    
    $entityManager = $this->getDoctrine()->getManager();
       
        $stock = new Stock();
        $form_stock = $this->createForm(StockType::class, $stock);
        $form_stock->handleRequest($request);
        $user = $this->getUser();
        $stock->setRegisteredBy($user)
              ->setDatePurchased(new \DateTime());

        if ($form_stock->isSubmitted() && $form_stock->isValid()) {    
               
=======
        $stockApprovalLevel = $settingRepository->findOneBy(['id'=>2])->getStockApprovalLevel();
        $id=$request->request->get('more');
        $stock=$stockRepository->findOneBy(['id'=>$id]);
        $user = $this-> getUser();
        $stockApproval = new StockApproval();
        $stockApproval->setApprovalLevel(1)
                      ->setStock($stock)
                      ->setApprovedBy($user);
        $form = $this->createForm(StockApprovalType::class, $stockApproval);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {

            if($stock->getQuantity() < $form->getData()->getApprovedQuantity() ){
                $this->addFlash('warning', 'The request cannot be executed because the approved quantity greater than the requested!');
                //
                return $this->render('product_details/index.html.twig', [
                    'stock' => $stock,
                    
                    'form'=> $form->createView(),
                ]);      
            }else{
            if($request->request->get('approve')){
                if(!$form->getData()->getApprovedQuantity()){

                $this->addFlash('error', 'Approved quantity is required!');
                return $this->render('product_details/index.html.twig', [
                    'stock' => $stock,
                    
                    'form'=> $form->createView(),
                ]);    

                }
                if($stockApprovalLevel-1 == count($stock->getStockApprovals())){
                    $stock->setApprovalStatus(1)
                          ->setApprovedQuantity($form->getData()->getApprovedQuantity());
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($stock);
                    $entityManager->flush();
                }
                $stockApproval->setDateOfApproval(new \DateTime())
                              ->setApprovalResponse(1);
                $this->addFlash('success', 'The request has been sucessfuly approved!');
            }
            elseif($request->request->get('reject')){
                $stockApproval->setApprovalResponse(2)
                              ->setDateOfApproval(new \DateTime());
                $stock->setApprovalStatus(2);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($stock);
                $entityManager->flush();
                $this->addFlash('error', 'The request has been successfully Rejected!');
            }
>>>>>>> ABI
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($stockApproval);
                $entityManager->flush();
                //$alertify->congrat('Congratulation !');
                $this->get('session')->getFlashBag()->set("confirm", array('engine' => 'modal', 'title' => "Wow", 'button_class' => "btn btn-primary btn-large", "body"=> "<div>Some info</div>"));

               return $this->redirectToRoute('stock_index');      
        }

    }
        return $this->render('product_details/index.html.twig', [
            'stock' => $stock,
            
            'form'=> $form->createView(),
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
     * @Route("/list/{id}", name="stock_list_delete", methods={"DELETE"})
     */
    public function deleteList(Request $request, StockList $stockList): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockList->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stockList);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }
<<<<<<< HEAD


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
=======
>>>>>>> ABI
}
