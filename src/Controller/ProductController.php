<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/product")
 */
class ProductController extends AbstractController
{
    /**
     * @Route("/", name="product_index", methods={"GET","POST"})
     */
    public function index(ProductRepository $productRepository, Request $request,PaginatorInterface $paginator): Response
    {
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $product=$productRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(ProductType::class, $product);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('product_index');
            }

            $queryBuilder=$productRepository->findProduct($request->query->get('name'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                2
            );
            return $this->render('product/index.html.twig', [
                'products' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $product = new Product;
        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($product);
            $entityManager->flush();

            return $this->redirectToRoute('product_index');
        }
        
        $search = $request->query->get('search');
        
        $queryBuilder=$productRepository->findProduct($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('product/index.html.twig', [
            'products' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);
    }

    /**
     * @Route("/{id}", name="product_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Product $product): Response
    {
        if ($this->isCsrfTokenValid('delete'.$product->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($product);
            $entityManager->flush();
        }

        return $this->redirectToRoute('product_index');
    }
}
