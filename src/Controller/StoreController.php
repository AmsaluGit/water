<?php

namespace App\Controller;

use App\Entity\Store;
use App\Form\StoreType;
use App\Repository\StoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
/**
 * @Route("/store")
 */
class StoreController extends AbstractController
{
    /**
     * @Route("/", name="store_index", methods={"GET","POST"})
     */
    public function index(StoreRepository $storeRepository, Request $request,PaginatorInterface $paginator): Response
    {
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $store=$storeRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(StoreType::class, $store);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('store_index');
            }

            $queryBuilder=$storeRepository->findAll($request->query->get('name'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                2
            );
            return $this->render('store/index.html.twig', [
                'stores' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $store = new Store;
        $form = $this->createForm(StoreType::class, $store);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($store);
            $entityManager->flush();

            return $this->redirectToRoute('store_index');
        }
        
        $search = $request->query->get('search');
        
        $queryBuilder=$storeRepository->findAll($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('store/index.html.twig', [
            'stores' => $data,
            'form' => $form->createView(),
            'edit' => false
        ]);
    }

    /**
     * @Route("/{id}", name="store_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Store $store): Response
    {
        if ($this->isCsrfTokenValid('delete'.$store->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($store);
            $entityManager->flush();
        }

        return $this->redirectToRoute('store_index');
    }
}
