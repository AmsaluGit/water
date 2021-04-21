<?php

namespace App\Controller;

use App\Entity\MaterialRecord;
use App\Form\MaterialRecordType;
use App\Repository\MaterialRecordRepository;
use App\Form\StockApprovalType;
use App\Repository\SettingRepository;
use App\Repository\StockApprovalRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\StockApproval;


/**
 * @Route("/material")
 */
class MaterialRecordController extends AbstractController
{
    /**
     * @Route("/", name="material_index", methods={"GET","POST"})
     */
    public function index(MaterialRecordRepository $materialRecordRepository, SettingRepository $settingRepository, Request $request, PaginatorInterface $paginator): Response
    {
          

        $entityManager = $this->getDoctrine()->getManager();
       
        $materialRecord = new MaterialRecord();
        $form = $this->createForm(MaterialRecordType::class, $materialRecord);
        $form->handleRequest($request);
        $user = $this->getUser();
        // $materialRecord->setRegisteredBy($user)
        //       ->setDatePurchased(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {    
               
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($materialRecord);
            $entityManager->flush();
            $this->addFlash("save",'New Stock Delivery Added');

            $materialRecordId = $materialRecord->getId();
            return $this->redirectToRoute('edit_material_index',['id'=>$materialRecordId]);
               
        }

        $qb = $materialRecordRepository->findAll();

        return $this->render('material_record/index1.html.twig', [
           'materialRecord' => $qb,
            'material_lists'=>$materialRecord->getId(),
            'add_item'=>false,
            'form'=> $form->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$materialRecord->getId(),
            
        ]);




    
        $stockApprovalLevel = $settingRepository->findOneBy(['code'=>'stock_approval_level'])->getValue();
        
        if($request->request->get('approve')){

            // dd($request->request->all());
            $note = $request->request->get('remark');
            $id = $request->request->get('approve');
            $materialRecord = $materialRecordRepository->find($id);
            $count = 0;
            foreach($materialRecord->getMaterialRecords() as $list){
                $listId = $list->getId();
                $var = $request->request->get("quantity$listId");
                if($var > $list->getQuantity()){
                    $this->addFlash('error', 'please make sure the approved quantity is less than the quantity!');
                    return $this->redirectToRoute('materialRecord_index');
                }else{
                if($request->request->get("quantity$listId") and $request->request->get("mySelect$listId") == "Approve some"){
                    $list->setApprovedQuantity($request->request->get("quantity$listId"))
                         ->setRemark($request->request->get("remark$listId"))
                         ->setApprovalStatus(1);
                }
                
                // if($request->request->get("mySelect$listId") == "Approve all"){
                //     $list->setApprovedQuantity($list->getQuantity())
                //          ->setApprovalStatus(1);
                // }
                
                // if($request->request->get("mySelect$listId") == "Reject"){
                //     $list->setApprovalStatus(2)
                //          ->setRemark($request->request->get("remark$listId"));
                //     $count = $count+1;
                
             }
             
            } 
            $user = $this->getUser();
            if ($count == count($materialRecord->getMaterialRecords())){
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
            
        }
        elseif($request->request->get('reject')){
            $user = $this->getUser();
            $id = $request->request->get('reject');
            $materialRecord = $materialRecordRepository->find($id);
            $materialRecord = $materialRecord->getMaterialRecords();
            foreach($materialRecord as $list){

                $listId = $list->getId();
                $list->setApprovalStatus(2);
                
                
            }
            $stock->setApprovedBy($user)
                //   ->setNote($request->request->get('remark'))
                  ->setApprovalStatus(2);
            $this->addFlash('save', 'The Stock Delivery has been  Rejected!');
        }

        

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();
    
            
      
        $queryBuilder=$materialRecordRepository->findMaterialRecord($request->query->get('search'));
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );
        
        $qb = $materialRecordRepository->findAll();
        return $this->render('material_record/index1.html.twig', [
            'materialRecords' => $data,
            // 'edit_list'=>$editlist,
            'materialRecord'=>$qb,

            'edit'=>false,

        ]);



    }  

    
    
     
    /**
     * @Route("/editmaterial/{id}", name="edit_material_index", methods={"GET","POST"})
     */
    public function EditMaterial(MaterialRecordRepository $materialRecordRepository,settingRepository $settingRepository, Request $request, $id ): Response
    {  
        
        $entityManager = $this->getDoctrine()->getManager();
        if($request->request->get('edit')){
            $materialRecordId=$request->request->get('edit');
            $materialRecord = $materialRecordRepository->find($materialRecordId);
        }elseif($request->request->get("parentId")){
            
            $materialRecord = $entityManager->getRepository(MaterialRecord::class)->find($request->request->get("parentId"));
        }
        else{
            $materialRecord = $materialRecordRepository->find($id);
        }
        $form = $this->createForm(MaterialRecordType::class, $materialRecord);
        $form->handleRequest($request);

        // $List = new MaterialRecord();
        // $form = $this->createForm(StockListType::class,$List);
        // $form->handleRequest($request);
        
        // edit info on stocks
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash("save",'Stock Updated');
            return $this->redirectToRoute('edit_material_index',['id'=>$materialRecordId]);
            
        }

        // add new item on the sell to sellsList
        if ($form->isSubmitted() && $form->isValid()) {
            
            $materialRecord = $entityManager->getRepository(MaterialRecord::class)->find($request->request->get("parentId"));
            $List->setMaterialRecord($materialRecord);
            $entityManager->persist($List);
            $entityManager->flush(); 
            $this->addFlash("save",'Item Added');
            return $this->redirectToRoute('edit_material_index',['id'=>$materialRecord->getId()]);
            
        }
        
        $qb = $materialRecordRepository->findAll();
        return $this->render('material_record/index1.html.twig', [
            'material_list' => $qb,
            'form' => $form->createView(),
            'add_item'=>true,
            'edit'=>$materialRecord->getId(),
            'edit_list'=>false,
            // 'material_lists'=>$List,
            'id'=>$materialRecord->getId(),
        ]);
    

       }
    
   

    /**
     * @Route("/list/{id}", name="material_list_delete", methods={"DELETE"})
     */
    public function deleteList(Request $request, MaterialRecord $materialRecord): Response
    {
        if ($this->isCsrfTokenValid('delete'.$materialRecord->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materialRecord);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }


    //  /**
    //  * @Route("/download/{id}", name="download_stock_index", methods={"POST","GET"})
    //  */
    // public function downloadList(StockListRepository $stockListRepository, Request $request, StockRepository $stockRepository, $id): Response
    // {

    //     $data = $stockRepository->find($id);
    //     // $sellsList = new SellsList();
    //     $qb = $stockListRepository->findBy(['stock' => $id]);
    //     // $theDate = new \DateTime();
    //     $theDate = date_create();
    //     $date = $theDate->format('Y-m-d');
    //     return $this->render('stock/2.html.twig', [
    //         'stock' => $data,
    //         'stock_list'=>$qb,
    //         'date' => $date,
    //     ]);
    // }
}
