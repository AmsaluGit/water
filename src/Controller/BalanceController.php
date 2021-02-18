<?php

namespace App\Controller;

use App\Entity\Product;
use App\Entity\ConsumptionRequest;
use App\Form\BalanceType;
use App\Form\ProductType;
use App\Repository\BalanceRepository;
use App\Repository\ProductRepository;
use App\Repository\ConsumptionRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/balance")
 */
class BalanceController extends AbstractController
{
    /**
     * @Route("/", name="balance_index", methods={"GET","POST"})
     */
    public function index(BalanceRepository $BalanceRepository, ConsumptionRequestRepository $ConsumptionRequestRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $em=$this->getDoctrine()->getManager();
        $conn=$em->getConnection();

        $sql = "select p.name as name, sum(cr.quantity) as quantity, sum(st.quantity) as total, sum(CASE WHEN st.approval_status =1 THEN st.approved_quantity END) as approved, sum(CASE WHEN st.approval_status =2 THEN st.approved_quantity END) as rejected from product as p inner join stock as st on st.product_id = p.id inner join consumption_request as cr on cr.product_id = p.id  GROUP BY p.name ";
        $stmt=$conn->prepare($sql);
        $stmt->execute();
        $data=$stmt->fetchAll();

    
        return $this->render('balance/index.html.twig', [
            'balances' => $data,
           
        ]);


        // if($request->request->get('Reply')){
        //     $id=$request->request->get('Reply');
        //     $balance=$balanceRepository->findOneBy(['id'=>$id]);
        //     $form = $this->createForm(BalanceType::class, $balance);
        //     $form->handleRequest($request);
    
        //     if ($form->isSubmitted() && $form->isValid()) {
        //         $this->getDoctrine()->getManager()->flush();
           
        //         return $this->redirectToRoute('balance_index');
        //     }

        //     $queryBuilder=$balanceRepository->findBalance($request->query->get('search'));
        //     $data=$paginator->paginate(
        //         $queryBuilder,
        //         $request->query->getInt('page',1),
        //         18
        //     );
           
           
        //     return $this->render('balance/index.html.twig', [
        //         'balances' => $data,
        //         'form' => $form->createView(),
        //         'request'=>$id
                
        //     ]);
        // }

        
    }
    
    /**
     * @Route("/{id}", name="productDetail_index", methods={"GET","POST"})
     */
    public function productDetail(ProductRepository $productRepository, Request $request, PaginatorInterface $paginator): Response   
    {
        
            $id=$request->request->get('reply');
            $product=$productRepository->findOneBy(['name'=>$id]);

            $ConsumptionRequest = new ConsumptionRequest();
            $user = $this->getUser();
            
                            
            $form = $this->createForm(BalanceType::class, $ConsumptionRequest);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                
                $ConsumptionRequest->setProduct($product)
                                ->setRequester($user)
                                ->setRequestedDate(new \DateTime());
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($ConsumptionRequest);
                $entityManager->flush();
                $this->addFlash('success', 'The Balance has been sucessfully requested!');

                return $this->redirectToRoute('balance_index');
            }

     return $this->render('balance/productDetail.html.twig', [
        
        'form' => $form->createView(),
        'product'=>$product,
        

    ]); 


 } 

   
}
