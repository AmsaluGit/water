<?php

namespace App\Controller;

use App\Entity\ConsumptionApproval;
use App\Entity\ConsumptionRequest;
use App\Form\ConsumptionApprovalType;
use App\Form\ConsumptionRequestType;
use App\Repository\ConsumptionApprovalRepository;
use App\Repository\SettingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ConsumptionRequestRepository;


/**
 * @Route("/consumption/approval")
 */
class ConsumptionApprovalController extends AbstractController
{
    /**
     * @Route("/", name="consumption_approval_index", methods={"GET", "POST"})
     */
    public function index(ConsumptionRequestRepository $consumptionRequestRepository,SettingRepository $settingRepository, Request $request, ConsumptionApprovalRepository $consumptionApprovalRepository): Response
    {
        $settingApprovalLevel = $settingRepository->findOneBy(['id'=>1])->getValue();

        if ($request->request->get('replay')) {
            $id = $request->request->get('replay');
            // $id = 1;
            $consumptionRequest=$consumptionRequestRepository->findOneBy(['id'=>$id]);
            $consumptionApproval = new ConsumptionApproval();
            $form = $this->createForm(ConsumptionApprovalType::class, $consumptionApproval);
            $form->handleRequest($request);
            $user = $this->getUser();
            if ($request->request->get('approve') || $request->request->get('reject'))
            {
                if ($request->request->get('approve')){
                    if($settingApprovalLevel-1 == count($consumptionRequest->getConsumptionApprovals())){
                        $consumptionRequest->setApprovalStatus(1);
                        $entityManager = $this->getDoctrine()->getManager();
                        $entityManager->persist($consumptionRequest);
                        $entityManager->flush();
                    }
                    $approveResponse =1;
                    $this->addFlash('success', 'The request has been sucessfuly approved!');               
                 }
                else{

                    $consumptionRequest->setApprovalStatus(2);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($consumptionRequest);
                    $entityManager->flush();
                    $approveResponse =2;
                    $this->addFlash('error', 'The request has been successfully Rejected!');

                    // $approveFlash =['danger', 'you have successfully disapproved the request!'];
                }

                $consumptionApproval->setConsumptionRequest($consumptionRequest)
                                    ->setApprovedBy($user)
                                    ->setDateOfApproval(new \DateTime())
                                    ->setApprovalLevel(1)
                                    ->setApprovalResponse($approveResponse);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($consumptionApproval);
                $entityManager->flush();

                return $this->redirectToRoute('consumption_index');  
            }
            return $this->render('consumption_approval/index.html.twig', [
                'consumption_approval' => $consumptionRequest,
                'form' => $form->createView(),

            ]);

        }
        else{
            return $this->redirectToRoute('consumption_index');
        }
        
    }
}
