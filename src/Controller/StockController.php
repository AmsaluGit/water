<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\StockApproval;
use App\Form\StockType;
use App\Form\StockApprovalType;
use App\Repository\StockRepository;
use App\Repository\StockApprovalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/stock")
 */
class StockController extends AbstractController
{
    /**
     * @Route("/", name="stock_index", methods={"GET","POST"})
     */
    public function index(StockRepository $stockRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $stock=$stockRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(stockType::class, $stock);
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
                'edit'=>$id
            ]);

        }

        $stock = new stock();
        $form = $this->createForm(StockType::class, $stock);
        $form->handleRequest($request);
        $stock ->setDatePurchased(new \DateTime());
        $stock->setRegisteredBy($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($stock);
            $entityManager->flush();

            return $this->redirectToRoute('stock_index');
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$stockRepository->findStock($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );

        return $this->render('stock/index.html.twig', [
            'stocks' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }
    
    /**
     * @Route("/{id}", name="product_detail_index", methods={"GET","POST"})
     */
    public function approveStock(StockApprovalRepository $stockApprovalRepository, StockRepository $stockRepository, Request $request): Response
    {   

        $id=$request->request->get('more');
        //$id = $request->request->get('more');
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
                //  return $this->redirectToRoute('product_details_index/'.$stock->getId());
            }else{
            if($request->request->get('approve')){
                $stockApproval->setDateOfApproval(new \DateTime())
                              ->setApprovalResponse(1);
                $this->addFlash('success', 'The request has been sucessfuly approved!');
            }
            elseif($request->request->get('reject')){
                $stockApproval->setApprovalResponse(2)
                              ->setDateOfApproval(new \DateTime());
                $this->addFlash('error', 'The request has been successfully Rejected!');
            }
            $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($stockApproval);
                $entityManager->flush();
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

        return $this->redirectToRoute('stock_index');
    }
}
