<?php

namespace App\Controller;

use App\Entity\ConsumptionRequestList;
use App\Entity\ConsumptionRequest;
use App\Form\ConsumptionRequestListType;
use App\Form\ConsumptionRequestType;
use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\ConsumptionRequestListRepository;
use App\Repository\ConsumptionRequestRepository;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;


/**
 * @Route("/consumption/requestlist")
 */
class ConsumptionRequestListController extends AbstractController
{
    /**
     * @Route("/", name="consumption_jjrequest_list_index", methods={"GET", "POST"})
     */

    public function index(ConsumptionRequestRepository $consumptionRequestRepository, SectionRepository $sectionRepository, Request $request, ConsumptionRequestListRepository $consumptionRequestListRepository, PaginatorInterface $paginator): Response
    { 
        $consumptionRequestList = new ConsumptionRequestList();
        $form1 = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
        $form1->handleRequest($request);

        //when edit submit
        if($request->request->get('p-edit') || isset($_GET['p-edit'])){
            if(isset($_GET['p-edit'])){
                $id =$_GET['p-edit'] ;
                $_GET['p-edit']=null;
            }
            else
                $id = $request->request->get('p-edit');

            $consumptionRequest = $consumptionRequestRepository->find($id);
            $form = $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
            $form->handleRequest($request);
            $consumptionRequestListAll = $consumptionRequestListRepository->findAll();
            return $this->render('consumption_request_list/index.html.twig', [
                'consumption_request' => $consumptionRequest,
                'form' => $form->createView(),
                'save_section' => true,
                'formList' => true,
                'form_list' => $form1->createView(),
                'consumption_list' => $consumptionRequestListAll,

            ]);
        }
        
        //when section_on edit is submit
        if($request->request->get('sec_save') || isset($_GET['context'])){
            if(isset($_GET['context'])){
                $id = $_GET['parent-id'];
                $_GET['context']= null;
                $_GET['parent-id']=null; 
            }

            else
                $id = $request->request->get('sec_save');
            $consumptionRequest = $consumptionRequestRepository->find($id);
            $form = $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
            $form->handleRequest($request);

            $consumptionRequestListAll = $consumptionRequestListRepository->findAll();


            if($form->isSubmitted() && $form->isValid()){
                $consumptionRequest->setRequestedDate(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($consumptionRequest);
                $entityManager->flush();
                $this->addFlash("success",'Updated !');
            }

            return $this->render('consumption_request_list/index.html.twig', [
                'consumption_request' => $consumptionRequest,
                'consumption_list' => $consumptionRequestListAll,
                'form' => $form->createView(),
                'form_list' => $form1->createView(),
                'save_section' => true,
                'formList' => true
            ]);

        } 
        //when list add is submit
        if($request->request->get('list_submit') || isset($_GET['context'])){
            if(isset($_GET['context'])){
                $id = $_GET['parent-id'];
                $_GET['context']=null;
                $_GET['parent-id'] =null;
            }
            else
                $id = $request->request->get('list_submit');
            if($form1->isSubmitted() && $form1->isValid()){
                $consumptionRequest = $consumptionRequestRepository->find($id);

                $form = $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
                $form->handleRequest($request);

                $entityManager = $this->getDoctrine()->getManager();
                $consumptionRequestList->setConsumptionRequest($consumptionRequest);
                $consumptionRequestList->setAvailable(4);
                $consumptionRequestList->setIssue(2);
                $entityManager->persist($consumptionRequestList);
                $entityManager->flush();
                //correction nedded here-----------------------------------------------------
                $user = $this->getUser();

                $consumptionRequest->setRequester($user);
                $consumptionRequest->setRequestedDate(new \DateTime());
                $consumptionRequest->setNote('i dont understand!');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($consumptionRequest);
                $entityManager->flush();
                // correction nedded end-----------------------------------------------------

                $consumptionRequestList = new ConsumptionRequestList();
                $form1 = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
    
                $consumptionRequestListAll = $consumptionRequestListRepository->findAll();
                $this->addFlash("success",'Successfully Added !');

           }
           return $this->render('consumption_request_list/index.html.twig', [
            'consumption_request' => $consumptionRequest,
            'consumption_list' => $consumptionRequestListAll,
            'form' => $form->createView(),
            'form_list' => $form1->createView(),
            'save_section' => true,
            'formList' => true,
            
        ]);

       }
        // return $this->redirectToRoute('consumption_request_list_new');
    } 

/**
 * @Route("/new", name="consumption_request_list_new", methods={"GET", "POST"})
 */
    public function newRequest(Request $request, ConsumptionRequestListRepository $consumptionRequestListRepository, ConsumptionRequestRepository $consumptionRequestRepository)
    {    
       //when new request button clicked
       $consumptionRequest = new ConsumptionRequest();
       $form = $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
       $form->handleRequest($request);

       if($request->request->get('new-list')){
            // $consumptionRequestListAll = $consumptionRequestListRepository->findAll();
            return $this->render('consumption_request_list/index.html.twig', [
                'consumption_request' => $consumptionRequest,
                'form' => $form->createView(),
                'save_section' => false,
                'formList' => false,
                // 'consumption_list' => $consumptionRequestListAll,

            ]);
       }

       // consumption request list form
       $user =$this->getUser();
       $consumptionRequestList = new ConsumptionRequestList();
       $form1 = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
       $form1->handleRequest($request);

       //when section_on edit is submit
       if($request->request->get('sec_save')){
           //-------------------------------------------
            if($request->request->get('sec_save') != "infinite" ){
                $id = $request->request->get('sec_save');
                $consumptionRequest = $consumptionRequestRepository->find($id);

                $form =$this->createForm(ConsumptionRequestType::class, $consumptionRequest);
                $form->handleRequest($request);
                
                if($form->isSubmitted() && $form->isValid()){
                    $this->getDoctrine()->getManager()->flush();
                    //----------------------------------------
                    // $consumptionRequest->setRequester($user);
                    // $consumptionRequest->setRequestedDate(new \DateTime());
                    // $consumptionRequest->setNote("i don't understand the purpose of this attribut");
                    // $entityManager = $this->getDoctrine()->getManager();
                    // $entityManager->persist($consumptionRequest);
                    // $entityManager->flush();
                    $this->addFlash("success",'Updated !');
                }
            }
           //==========================================
           else{
                
                if($form->isSubmitted() && $form->isValid()){
                    $consumptionRequest->setRequester($user);
                    $consumptionRequest->setRequestedDate(new \DateTime());
                    $consumptionRequest->setNote("i don't understand the purpose of this attribut");
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($consumptionRequest);
                    $entityManager->flush();
                    $this->addFlash("success",'successfuly added !');
                }
           }
            return $this->render('consumption_request_list/index.html.twig', [
                'consumption_request' => $consumptionRequest,
                // 'consumption_list' => $consumptionRequestListAll,
                'form' => $form->createView(),
                'form_list' => $form1->createView(),
                'save_section' => true,
                'formList' => true
            ]);
        }    

                //when list add is submit
                if($request->request->get('list_submit')){
                    if($form1->isSubmitted() && $form1->isValid()){
                        $id = $request->request->get('list_submit');
        
                        $consumptionRequest = $consumptionRequestRepository->find($id);
        
                        $form = $this->createForm(ConsumptionRequestType::class, $consumptionRequest);
                        $form->handleRequest($request);
        
                        $entityManager = $this->getDoctrine()->getManager();
                        $consumptionRequestList->setConsumptionRequest($consumptionRequest);
                        $consumptionRequestList->setAvailable(4);
                        $consumptionRequestList->setIssue(2);
                        $entityManager->persist($consumptionRequestList);
                        $entityManager->flush();
                        //-----------------------------------------------------
                        $user = $this->getUser();
        
                        $consumptionRequest->setRequester($user);
                        $consumptionRequest->setRequestedDate(new \DateTime());
                        $consumptionRequest->setNote('i dont understand!');
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($consumptionRequest);
                        $entityManager->flush();
                        $consumptionRequestList = new ConsumptionRequestList();
                    
                        $form1 = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
                        //-----------------------------------------------------
                        $consumptionRequestListAll = $consumptionRequestListRepository->findAll();
                        $this->addFlash("success",'Item Successfully Added !');
        
                       return $this->render('consumption_request_list/index.html.twig', [
                        'consumption_request' => $consumptionRequest,
                        'consumption_list' => $consumptionRequestListAll,
                        'form' => $form->createView(),
                        'form_list' => $form1->createView(),
                        'save_section' => true,
                        'formList' => true,
                        
                    ]);
                    }
                }   
}

// /**
//  * @Route("/{id}", name="consumption_request_list_delete", methods={"DELETE"})
//  */
// public function childDelete(Request $request, ConsumptionRequestList $consumptionRequestList): Response
// {
//     if ($this->isCsrfTokenValid('delete'.$consumptionRequestList->getId(), $request->request->get('_token'))) {
        
//         $entityManager = $this->getDoctrine()->getManager();
//         $entityManager->remove($consumptionRequestList);
//         $entityManager->flush();
//     }
//     $_GET['p-edit'] =$request->request->get('editParent');
//     return $this->redirectToRoute('consumption_request_list_index', $_GET);
// }
// /**
//  * @Route("/{id}/edit", name="consumption_request_list_edit", methods={"GET","POST"})
//  */
// public function childEdit(Request $request, ConsumptionRequestList $consumptionRequestList): Response
// {
//         $form = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
//         $form->handleRequest($request);

//         if ($form->isSubmitted() && $form->isValid()) {
//             $context = $request->request->get('context');
//             $parentID = $request->request->get('parent-id');
//             $_GET['context'] = $context;
//             $_GET['parent-id'] = $parentID;
//             $this->getDoctrine()->getManager()->flush();
            
//             return $this->redirectToRoute('consumption_request_list_index', $_GET);
//         }
        
//         if($request->request->get('backlist')){
//             $_GET['context'] = $request->request->get('backlist');
//             $parentID =$request->request->get('parent-id');
//             $_GET['parent-id'] =$parentID;

//             return $this->redirectToRoute('consumption_request_list_index', $_GET);
//         }

//         return $this->render('consumption_request_list/edit.html.twig', [
//             'consumption_request_list' => $consumptionRequestList,
//             'form_edit' => $form->createView(),
//             'context' =>$request->request->get('context'),
//             'parentid' =>$request->request->get('parent-id'),
//         ]);

// }

    // /**
    //  * @Route("/new", name="consumption_request_list_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $consumptionRequestList = new ConsumptionRequestList();
    //     $form = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($consumptionRequestList);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('consumption_request_list_index');
    //     }

    //     return $this->render('consumption_request_list/new.html.twig', [
    //         'consumption_request_list' => $consumptionRequestList,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="consumption_request_list_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, ConsumptionRequestList $consumptionRequestList): Response
    // {
    //     $form = $this->createForm(ConsumptionRequestListType::class, $consumptionRequestList);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('consumption_request_list_index');
    //     }

    //     return $this->render('consumption_request_list/edit.html.twig', [
    //         'consumption_request_list' => $consumptionRequestList,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="consumption_request_list_delete", methods={"DELETE"})
    //  */
    // public function delete(Request $request, ConsumptionRequestList $consumptionRequestList): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$consumptionRequestList->getId(), $request->request->get('_token'))) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->remove($consumptionRequestList);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('consumption_request_list_index');
    // }
}
