<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/category")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route("/", name="category_index", methods={"GET","POST"})
     */
    public function index(CategoryRepository $categoryRepository, Request $request, PaginatorInterface $paginator): Response
    {
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $category=$categoryRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(CategoryType::class, $category);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('category_index');
            }

            $queryBuilder=$categoryRepository->findCategory($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('category/index.html.twig', [
                'categories' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('category_index');
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$categoryRepository->findCategory($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('category/index.html.twig', [
            'categories' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);;
    }

    // /**
    //  * @Route("/new", name="category_new", methods={"GET","POST"})
    //  */
    // public function new(Request $request): Response
    // {
    //     $category = new Category();
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager = $this->getDoctrine()->getManager();
    //         $entityManager->persist($category);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('category_index');
    //     }

    //     return $this->render('category/new.html.twig', [
    //         'category' => $category,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="category_show", methods={"GET"})
    //  */
    // public function show(Category $category): Response
    // {
    //     return $this->render('category/show.html.twig', [
    //         'category' => $category,
    //     ]);
    // }

    // /**
    //  * @Route("/{id}/edit", name="category_edit", methods={"GET","POST"})
    //  */
    // public function edit(Request $request, Category $category): Response
    // {
    //     $form = $this->createForm(CategoryType::class, $category);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $this->getDoctrine()->getManager()->flush();

    //         return $this->redirectToRoute('category_index');
    //     }

    //     return $this->render('category/edit.html.twig', [
    //         'category' => $category,
    //         'form' => $form->createView(),
    //     ]);
    // }

    // /**
    //  * @Route("/{id}", name="category_delete", methods={"DELETE"})
    //  */
    public function delete(Request $request, Category $category): Response
    {
        if ($this->isCsrfTokenValid('delete'.$category->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($category);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_index');
    }
}
