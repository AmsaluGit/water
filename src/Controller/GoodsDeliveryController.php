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
        

        if($request->request->get('approve')){
            $note = $request->request->get('remark');
            $id = $request->request->get('approve');
            $sells = $sellsRepository->find($id);
            $user = $this->getUser();
            $sells->setApprovedBy($user)
                  ->setNote($note)
                  ->setApprovalStatus(1);
            $this->addFlash('save', 'The Sell has been approved!');
        }
        elseif($request->request->get('reject')){
            $user = $this->getUser();
            $sells->setApprovedBy($user)
                  ->setNote($request->request->get('remark'))
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Sell has been  Rejected!');
        }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
        $queryBuilder=$sellsRepository->findAll();
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        
        $qb = $sellsListRepository->findAll();
        return $this->render('goods_delivery/index.html.twig', [
            'sells' => $data,
            'edit'=>false,
            'sellslist'=>$qb,
        ]);
    }
    


    /**
     * @Route("/new", name="new_sell_index", methods={"GET","POST"})
     */
    public function NewSell(SellsListRepository $sellsListRepository,settingRepository $settingRepository, Request $request, SellsRepository $sellsRepository ): Response
    {  
        
        // dd($request->request->all());
        $entityManager = $this->getDoctrine()->getManager();
        if ($request->request->get("parentId")){
            $sells = $entityManager->getRepository(Sells::class)->find($request->request->get("parentId"));
        }else{
            $sells = new Sells();
        }
        
        $sellsList = new SellsList();
        $form_sells = $this->createForm(SellsType::class, $sells);
        $form_sells->handleRequest($request);

        $addItems = false;

        if ($form_sells->isSubmitted() && $form_sells->isValid()) {    
               
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($sells);
            $entityManager->flush();
            $this->addFlash("save",'saved');

            $sellsId = $sells->getId();
            $addItems = true; 
               
        }

        // $sell = $sellsRepository->findOneBy(['id'=>$sellsId]);
      
        $form_sells_list = $this->createForm(SellsListType::class, $sellsList);
        $form_sells_list->handleRequest($request);      
        $entityManager = $this->getDoctrine()->getManager();

        if ($form_sells_list->isSubmitted() && $form_sells_list->isValid()) {  
            // dd($request->request->get("parentId"));  
            $sells2 = $entityManager->getRepository(Sells::class)->find($request->request->get("parentId"));
          
            $sellsList->setSells($sells2);

            $entityManager->persist($sellsList);
            $entityManager->flush();   
            $this->addFlash("save",'saved2');
            $addItems = true;
            
        }
        if($sells){
            $qb=$sellsListRepository->findBy(['sells'=>$sells]);
        }else{
            $qb=null;
        }

        return $this->render('goods_delivery/goods_form.html.twig', [
            'sells_list' => $qb,
            'sells_lists'=>$sells->getId(),
            'add_item'=>$addItems,
            'form_sells'=> $form_sells->createView(),
            'form_sells_list'=> $form_sells_list->createView(),
            'edit'=>$sells->getId(),
            'edit_list'=>false,
            'id'=>$sells->getId(),
            
        ]);
       }

    /**
     * @Route("/editsells/{id}", name="edit_sells_index", methods={"GET","POST"})
     */
    public function EditSells(SellsListRepository $sellsListRepository,settingRepository $settingRepository, Request $request, SellsRepository $sellsRepository ): Response
    {  
        $entityManager = $this->getDoctrine()->getManager();
        if($request->request->get('edit')){
            $sellsId=$request->request->get('edit');
            $sell = $sellsRepository->find($sellsId);
        }else{
            
            $sell = $entityManager->getRepository(Sells::class)->find($request->request->get("parentId"));
        }

        $form_sells = $this->createForm(SellsType::class, $sell);
        $form_sells->handleRequest($request);

        $sellsList = new SellsList();
        $form_sells_list = $this->createForm(SellsListType::class,$sellsList);
        $form_sells_list->handleRequest($request);
        
        if ($form_sells_list->isSubmitted() && $form_sells_list->isValid()) {

            $sellsList->setSells($sell);
            $entityManager->persist($sellsList);
            $entityManager->flush(); 
            $this->addFlash("save",'saved1');

            
        }

        if ($form_sells->isSubmitted() && $form_sells->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'saved');
            
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
    public function EditSellsList(SellsListRepository $sellsListRepository,settingRepository $settingRepository, Request $request, SellsRepository $sellsRepository ): Response
    {  
        $sellsId = null;
        $sellsListId = null;


        $sellsListId=$request->request->get('edit_list');
        
        $sellsList=$sellsListRepository->find($sellsListId);
        $sell = $sellsList->getSells();
        $form_sells_list = $this->createForm(SellsListType::class, $sellsList);
        $form_sells_list->handleRequest($request);

        $form_sells = $this->createForm(SellsType::class,$sell);
        $form_sells->handleRequest($request);

        if ($form_sells_list->isSubmitted() && $form_sells_list->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'saved');
            // return $this->redirectToRoute('goods_delivery_index');
        }

        $qb=$sellsListRepository->findBy(['sells'=>$sell]);
        return $this->render('goods_delivery/goods_form.html.twig', [
            'sells_list' => $qb,
            'form_sells' => $form_sells->createView(),
            'form_sells_list' => $form_sells_list->createView(),
            'add_item'=>true,
            'edit'=>false,
            'edit_list'=>$sellsListId,
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

        return $this->redirectToRoute('goods_delivery_index');
    }
}
