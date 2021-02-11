<?php

namespace App\Controller;

use App\Entity\Stock;
use App\Entity\StockApproval;
use App\Repository\StockRepository;
use App\Repository\StockApprovalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/approval")
 */
class ApprovalStatusController extends AbstractController
{
    /**
     * @Route("/{id}", name="approval_status_index", methods={"GET","POST"})
     */
    public function index(StockApprovalRepository $stockApprovalRepository, StockRepository $stockRepository, Request $request,PaginatorInterface $paginator): Response
    {   
        
        $id=$request->request->get('id');
       
        //$id = $request->request->get('approvalStatus');
        $stock=$stockRepository->findOneBy(['id'=>$id]);
        $stockApproval = $stockApprovalRepository->findApprovalStatus($stock);
         
        return $this->render('approval_status/index.html.twig', [
            'stock_approvals' => $stockApproval,
        ]);
    }
    
}
