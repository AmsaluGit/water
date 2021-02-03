<?php

namespace App\Controller;

use App\Entity\Department;
use App\Form\DepartmentType;
use App\Repository\DepartmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/department")
 */
class DepartmentController extends AbstractController
{
    /**
     * @Route("/", name="department_index", methods={"GET","POST"})
     */
    public function index(DepartmentRepository $departmentRepository, Request $request, PaginatorInterface $paginator): Response
    {

        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $department=$departmentRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(DepartmentType::class, $department);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('department_index');
            }

            $queryBuilder=$departmentRepository->findDepartment($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                2
            );
            return $this->render('department/index.html.twig', [
                'departments' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $department = new Department();
        $form = $this->createForm(DepartmentType::class, $department);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($department);
            $entityManager->flush();

            return $this->redirectToRoute('department_index');
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$departmentRepository->findDepartment($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            2
        );
        return $this->render('department/index.html.twig', [
            'departments' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
    }

    /**
     * @Route("/{id}", name="department_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Department $department): Response
    {
        if ($this->isCsrfTokenValid('delete'.$department->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($department);
            $entityManager->flush();
        }

        return $this->redirectToRoute('department_index');
    }
}
