<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\User;
use App\Entity\StockApproval;
use App\Form\StockApprovalType;
use App\Repository\StockRepository;
use App\Repository\StockApprovalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/product/details")
 */
class ProductDetailsController extends AbstractController
{
    /**
     * @Route("/", name="product_details_index", methods={"GET","POST"})
     */
    public function index(StockApprovalRepository $stockApprovalRepository, StockRepository $stockRepository, Request $request): Response
    {   
        
        $id=2;
        $stock=$stockRepository->findOneBy(['id'=>$id]);
        $user = $this->getUser();
        $stockApproval = new StockApproval();
        $stockApproval->setApprovalLevel(1)
                      ->setStock($stock)
                      ->setApprovedBy($user);
        $form = $this->createForm(StockApprovalType::class, $stockApproval);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            if($request->request->get('approve')){
                $stockApproval->setDateOfApproval(new \DateTime())
                              ->setApprovalResponse(1);
            }
            elseif($request->request->get('reject')){
                $stockApproval->setApprovalResponse(2)
                              ->setDateOfApproval(new \DateTime());
            }
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($stockApproval);
                $entityManager->flush();
                return $this->redirectToRoute('product_index');      
        }

        
        return $this->render('product_details/index.html.twig', [
            'stock' => $stock,
            'form'=> $form->createView(),
        ]);
    }
    
    /**
     * @Route("/{id}", name="stock_approval_delete", methods={"DELETE"})
     */
    public function delete(Request $request, StockApproaval $stockApproval): Response
    {
        if ($this->isCsrfTokenValid('delete'.$stockApproval->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($stockApproval);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_details_index');
    }
}
