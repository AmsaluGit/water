<?php

namespace App\Controller;

use App\Entity\Section;
use App\Form\SectionType;
use App\Repository\SectionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/section")
 */
class SectionController extends AbstractController
{
 
    /**
     * @Route("/", name="section_index", methods={"GET","POST"})
     */
    public function index(SectionRepository $sectionRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $section=$sectionRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(SectionType::class, $section);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('section_index');
            }

            $queryBuilder=$sectionRepository->findSection($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                2
            );
            return $this->render('section/index.html.twig', [
                'sections' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $section = new Section();
        $form = $this->createForm(SectionType::class, $section);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($section);
            $entityManager->flush();

            return $this->redirectToRoute('section_index');
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$sectionRepository->findSection($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('section/index.html.twig', [
            'sections' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }

    /**
     * @Route("/{id}", name="section_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Section $section): Response
    {
        if ($this->isCsrfTokenValid('delete'.$section->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($section);
            $entityManager->flush();
        }

        return $this->redirectToRoute('section_index');
    }
}
