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
          
        $this->denyAccessUnlessGranted("material_record_list");
        $entityManager = $this->getDoctrine()->getManager();
       
        $materialRecord = new MaterialRecord();
        $form = $this->createForm(MaterialRecordType::class, $materialRecord);
        $form->handleRequest($request);
        $user = $this->getUser();
        $materialRecord
              ->setDate(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {    
            $this->denyAccessUnlessGranted("material_record_new");
            $entityManager = $this->getDoctrine()->getManager();
            
            $entityManager->persist($materialRecord);
            $entityManager->flush();
            $this->addFlash("save",'New Stock Delivery Added');

            $materialRecordId = $materialRecord->getId();
            return $this->redirectToRoute('edit_material_index',['id'=>$materialRecordId]);
               
        }

        $qb = $materialRecordRepository->findAll();
        // $qb=$materialRecordRepository->findBy(['materialRecord'=>$materialRecord]);

        return $this->render('material_record/index1.html.twig', [
           'materialRecord' => $qb,
            'material_lists'=>$materialRecord->getId(),
            'add_item'=>false,
            'form'=> $form->createView(),
            'edit'=>false,
            'edit_list'=>false,
            'id'=>$materialRecord->getId(),
            
        ]);
        

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
        $this->denyAccessUnlessGranted("material_record_edit");
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
            return $this->redirectToRoute('edit_material_index',['id'=>$materialRecord->getId()]);
            
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
            'materialRecord' => $qb,
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
        $this->denyAccessUnlessGranted("material_record_delete");
        if ($this->isCsrfTokenValid('delete'.$materialRecord->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($materialRecord);
            $entityManager->flush();
        }

        return $this->redirect($request->headers->get('referer'));
    }


}
