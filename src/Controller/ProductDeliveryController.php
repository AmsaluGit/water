<?php

namespace App\Controller;


use Knp\Component\Pager\PaginatorInterface;
use App\Entity\ProductDelivery;
use App\Entity\ProductDeliveryList;
use App\Form\ProductDeliveryType;
use App\Form\ReportType;
use App\Form\ProductDeliveryListType;
use App\Repository\ProductDeliveryRepository;
use App\Repository\ProductDeliveryListRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SettingRepository;
use DateTime;

/**
 * @Route("/product_delivery")
 */
class ProductDeliveryController extends AbstractController
{


/**
   * @Route("/", name="product_delivery_index", methods={"GET","POST"})
*/
public function index(ProductDeliveryRepository $ProductDeliveryRepository,SettingRepository $settingRepository, Request $request, PaginatorInterface $paginator, ProductDeliveryListRepository $ProductDeliveryListRepository): Response
{

    $this->denyAccessUnlessGranted("product_delivery_list");

    if($request->request->get('approve')){
        $this->denyAccessUnlessGranted("product_delivery_approval");
        // dd($request->request->all());
        $note = $request->request->get('remark');
        $id = $request->request->get('approve');
        $ProductDelivery = $ProductDeliveryRepository->find($id);
        
        foreach($ProductDelivery->getProductDeliveryLists() as $list){
            $listId = $list->getId();
            $var = $request->request->get("quantity$listId");
            if($var > $list->getQuantity()){
                $this->addFlash('error', 'please make sure the approved quantity is less than the quantity!');
                return $this->redirectToRoute('product_delivery_index');
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
        $ProductDelivery->setApprovedBy($user)
              ->setDeliveryDate(new \DateTime())
              ->setNote($note)
              ->setApprovalStatus(1);
        $this->addFlash('save', 'The ProductDelivery has been approved!');
    }
    elseif($request->request->get('reject')){
        $this->denyAccessUnlessGranted("product_delivery_approval");
        $user = $this->getUser();
        $id = $request->request->get('reject');
        $ProductDelivery = $ProductDeliveryRepository->find($id);
        $ProductDeliveryList = $ProductDelivery->getProductDeliveryLists();
        foreach($ProductDeliveryList as $list){

            $listId = $list->getId();
            $list->setApprovalStatus(2);

        }
        $ProductDelivery->setApprovedBy($user)
              ->setDeliveryDate(new \DateTime())
              ->setNote($request->request->get('remark'))
              ->setApprovalStatus(2);
        $this->addFlash('save', 'The Delivery has not been approved!');
    }

    

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();


  
    $queryBuilder=$ProductDeliveryRepository->findProduct($request->query->get('search'));
             $data=$paginator->paginate(
             $queryBuilder,
             $request->query->getInt('page',1),
           18
        );
    
    $qb = $ProductDeliveryListRepository->findAll();
    return $this->render('product_delivery/index.html.twig', [
        'ProductDelivery' => $data,
        // 'edit_list'=>$editlist,
        'ProductDeliverylist'=>$qb,
        'edit'=>false,

    ]);
}



/**
 * @Route("/new", name="new_product_delivery_index", methods={"GET","POST"})
 */
public function NewProductDelivery(ProductDeliveryListRepository $ProductDeliveryListRepository,settingRepository $settingRepository, Request $request, ProductDeliveryRepository $ProductDeliveryRepository ): Response
{  
    $this->denyAccessUnlessGranted("product_delivery_new");
    $entityManager = $this->getDoctrine()->getManager();
   $pd =  $ProductDeliveryRepository->findBiggestSerial();
//    dd($pd);
//    $serial_num = $pd->getSerial();

   $serial_num = 0;

   if($pd){
       $serial_num = $pd[0]->getSerial() + 1 ;
   }

   
    $ProductDelivery = new ProductDelivery();
    $ProductDelivery->setDeliveryDate(new \DateTime());
    $ProductDelivery->setSerial($serial_num);
    $form_ProductDelivery = $this->createForm(ProductDeliveryType::class, $ProductDelivery);
    $form_ProductDelivery->handleRequest($request);
    $user = $this->getUser();
    $ProductDelivery->setReceivedBy($user)
                    ->setApprovalStatus(3);
    

    if ($form_ProductDelivery->isSubmitted() && $form_ProductDelivery->isValid()) {    
           
        $entityManager = $this->getDoctrine()->getManager();
        
        $entityManager->persist($ProductDelivery);
        $entityManager->flush();
        $this->addFlash("save",'New Delivery Added');

        $ProductDeliveryId = $ProductDelivery->getId();
        return $this->redirectToRoute('edit_ProductDelivery_index',['id'=>$ProductDeliveryId]);
           
    }
  
    if($ProductDelivery){
        $qb=$ProductDeliveryListRepository->findBy(['productDelivery'=>$ProductDelivery]);
    }else{
        $qb=null;
    }

    return $this->render('product_delivery/product_delivery_form.html.twig', [
        'ProductDelivery_list' => $qb,
        'ProductDelivery_lists'=>$ProductDelivery->getId(),
        'add_item'=>false,
        'form_ProductDelivery'=> $form_ProductDelivery->createView(),
        'edit'=>false,
        'edit_list'=>false,
        'id'=>$ProductDelivery->getId(),
        
    ]);
   }

/**
 * @Route("/editProductDelivery/{id}", name="edit_ProductDelivery_index", methods={"GET","POST"})
 */
public function EditProductDelivery(ProductDeliveryListRepository $ProductDeliveryListRepository, Request $request, ProductDeliveryRepository $ProductDeliveryRepository,$id ): Response
{  
    $this->denyAccessUnlessGranted("product_delivery_edit");
    
    $entityManager = $this->getDoctrine()->getManager();
    if($request->request->get('edit')){
        $ProductDeliveryId=$request->request->get('edit');
        $ProductDelivery = $ProductDeliveryRepository->find($ProductDeliveryId);
    }elseif($request->request->get("parentId")){
        
        $ProductDelivery = $entityManager->getRepository(ProductDelivery::class)->find($request->request->get("parentId"));
    }
    else{
        $ProductDelivery = $ProductDeliveryRepository->find($id);
    }
    $form_ProductDelivery = $this->createForm(ProductDeliveryType::class, $ProductDelivery);
    $form_ProductDelivery->handleRequest($request);

    $ProductDeliveryList = new ProductDeliveryList();
    $form_ProductDelivery_list = $this->createForm(ProductDeliveryListType::class,$ProductDeliveryList);
    $form_ProductDelivery_list->handleRequest($request);
    
    // edit info on ProductDelivery
    if ($form_ProductDelivery->isSubmitted() && $form_ProductDelivery->isValid()) {
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("save",'ProductDelivery Updated');
        return $this->redirectToRoute('edit_ProductDelivery_index',['id'=>$ProductDeliveryId]);
        
    }

    // add new item on the ProductDelivery to ProductDeliveryList
    if ($form_ProductDelivery_list->isSubmitted() && $form_ProductDelivery_list->isValid()) {
        
        $productDelivery = $entityManager->getRepository(ProductDelivery::class)->find($request->request->get("parentId"));
        $ProductDeliveryList->setProductDelivery($productDelivery);
        $entityManager->persist($ProductDeliveryList);
        $entityManager->flush(); 
        $this->addFlash("save",'Item Added');
        return $this->redirectToRoute('edit_ProductDelivery_index',['id'=>$productDelivery->getId()]);
        
    }
    
    $qb=$ProductDeliveryListRepository->findBy(['productDelivery'=>$ProductDelivery]);
    return $this->render('product_delivery/product_delivery_form.html.twig', [
        'ProductDelivery_list' => $qb,
        'form_ProductDelivery' => $form_ProductDelivery->createView(),
        'form_ProductDelivery_list' => $form_ProductDelivery_list->createView(),
        'add_item'=>true,
        'edit'=>$ProductDelivery->getId(),
        'edit_list'=>false,
        'ProductDelivery_lists'=>$ProductDeliveryList,
        'id'=>$ProductDelivery->getId(),
    ]);


   }
/**
 * @Route("/editlist/{id}", name="edit_ProductDelivery_list_index", methods={"GET","POST"})
 */
public function EditProductDeliveryList(ProductDeliveryListRepository $ProductDeliveryListRepository,settingRepository $settingRepository, Request $request, ProductDeliveryRepository $ProductDeliveryRepository,$id ): Response
{  
    $this->denyAccessUnlessGranted("product_delivery_edit");
  
    $ProductDeliveryList = $ProductDeliveryListRepository->find($id);

    $ProductDelivery = $ProductDeliveryList->getProductDelivery();
    $form_ProductDelivery_list = $this->createForm(ProductDeliveryListType::class, $ProductDeliveryList);
    $form_ProductDelivery_list->handleRequest($request);

    $form_ProductDelivery = $this->createForm(ProductDeliveryType::class,$ProductDelivery);
    $form_ProductDelivery->handleRequest($request);

    if ($form_ProductDelivery_list->isSubmitted() && $form_ProductDelivery_list->isValid()) {
        $this->getDoctrine()->getManager()->flush();
        $this->addFlash("save",'Item Updated');
        return $this->redirect($request->headers->get('referer'));
    }

    $qb=$ProductDeliveryListRepository->findBy(['productDelivery'=>$ProductDelivery]);
    return $this->render('product_delivery/product_delivery_form.html.twig', [
        'ProductDelivery_list' => $qb,
        'form_ProductDelivery' => $form_ProductDelivery->createView(),
        'form_ProductDelivery_list' => $form_ProductDelivery_list->createView(),
        'add_item'=>true,
        'edit'=>false,
        'edit_list'=>true,
        'ProductDelivery_lists'=>$ProductDeliveryList,
        'id'=>$ProductDelivery->getId(),  
    ]);

   }

/**
 * @Route("/{id}", name="Product_delivery_delete", methods={"DELETE"})
 */
public function delete(Request $request, ProductDelivery $ProductDelivery): Response
{
    $this->denyAccessUnlessGranted("product_delivery_delete");
    if ($this->isCsrfTokenValid('delete'.$ProductDelivery->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ProductDelivery);
        $entityManager->flush();
    }
    return $this->redirect($request->headers->get('referer'));
}
/**
 * @Route("/child/{id}", name="ProductDelivery_list_delete", methods={"DELETE"})
 */
public function deleteList(Request $request, ProductDeliveryList $ProductDelivery): Response
{
    $this->denyAccessUnlessGranted("product_delivery_delete");
    if ($this->isCsrfTokenValid('delete'.$ProductDelivery->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($ProductDelivery);
        $entityManager->flush();
    }
    
    return $this->redirect($request->headers->get('referer'));
}

/**
 * @Route("/parent", name="ProductDelivery_list_print", methods={"POST","GET"})
 */
public function printList(Request $request, ProductDeliveryListRepository $ProductDeliveryListRepository, ProductDeliveryRepository $ProductDeliveryRepository): Response
{    $id = $request->request->get('id');

    $ProductDelivery = $ProductDeliveryRepository->find($id);
    $ProductDeliveryList = $ProductDeliveryListRepository->findBy(['productDelivery'=>$ProductDelivery]);
    
    // $product = $ProductDeliveryRepository->findById($id);
    // $handover = $product->getHandoverBy();
    // $list = $ProductDeliveryListRepository->findProductDeliveryList($id);
    
    
    
    return $this->render('product_delivery/5.html.twig', [
        'product' => $ProductDelivery,
        'ProductDelivery_list' => $ProductDeliveryList
        ]);
}

/**
 * @Route("/report", name="ProductDelivery_report", methods={"POST","GET"})
 */
public function report(Request $request, ProductDeliveryListRepository $ProductDeliveryListRepository, ProductDeliveryRepository $ProductDeliveryRepository): Response
{   

    $this->denyAccessUnlessGranted("report");
    if($request->request->get('radio')== 1){
       
        if($request->request->get('range_generate')){
            $start = $request ->request->get('start');
            $end = $request ->request->get('end');

            $ProductDelivery = $ProductDeliveryRepository->findDateRangeResult($start, $end,1);

            return $this->render('product_delivery/report2.html.twig', [
                'ProductDelivery' => $ProductDelivery,
               ]);
    
              }
         else {
            if($request->request->get('one_month')){

                
                $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,1,1);
                return $this->render('product_delivery/report2.html.twig', [
                    'ProductDelivery' => $ProductDelivery,
                   ]);
        
            }
            elseif($request->request->get('three_month')){
                
                $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,3,1);
                return $this->render('product_delivery/report2.html.twig', [
                    'ProductDelivery' => $ProductDelivery,
                   ]);
        
            }
            elseif($request->request->get('six_month')){
                $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,6,1);
                return $this->render('product_delivery/report2.html.twig', [
                    'ProductDelivery' => $ProductDelivery,
                   ]);
        
            }
            elseif($request->request->get('one_year')){
                $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,12,1);
                return $this->render('product_delivery/report2.html.twig', [
                    'ProductDelivery' => $ProductDelivery,
                   ]);
        
            
            }
        
            
         }
        }
     elseif($request->request->get('radio')==2){
        if($request->request->get('range_generate')){
            $start = $request ->request->get('start');
            $end = $request ->request->get('end');

            $ProductDelivery = $ProductDeliveryRepository->findDateRangeResult($start, $end,2);

            return $this->render('product_delivery/report2.html.twig', [
                'ProductDelivery' => $ProductDelivery,
               ]);
    
              }
         else {
        if($request->request->get('one_month')){
            
            $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,1,2);
            return $this->render('product_delivery/report2.html.twig', [
                'ProductDelivery' => $ProductDelivery,
               ]);
    
           
        }
        elseif($request->request->get('three_month')){
            
            $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,3,2);
            return $this->render('product_delivery/report2.html.twig', [
                'ProductDelivery' => $ProductDelivery,
               ]);
    
        }
        elseif($request->request->get('six_month')){
            $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,6,2);
            return $this->render('product_delivery/report2.html.twig', [
                'ProductDelivery' => $ProductDelivery,
               ]);
    
        }
        elseif($request->request->get('one_year')){
            $ProductDelivery = $ProductDeliveryRepository->findDateInterval(new \DateTime,12,2);
            return $this->render('product_delivery/report2.html.twig', [
                'ProductDelivery' => $ProductDelivery,
               ]);
    
        }
        
            
         }
        }
         
    
    return $this->render('product_delivery/report.html.twig', [
        'product' => null,
       'ProductDelivery_list' => null
       ]);
}



}