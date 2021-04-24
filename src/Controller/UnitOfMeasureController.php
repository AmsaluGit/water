<?php

namespace App\Controller;

use App\Entity\UnitOfMeasure;
use App\Form\UnitOfMeasureType;
use App\Repository\UnitOfMeasureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/unitofmeasure")
 */
class UnitOfMeasureController extends AbstractController
{
    /**
     * @Route("/", name="unit_of_measure_index", methods={"GET","POST"})
     */
    public function index(PaginatorInterface $paginator, Request $request, UnitOfMeasureRepository $UnitOfMeasureRepository): Response
    {

        $this->denyAccessUnlessGranted("system_setting");
        if($request->request->get('edit')){
            $id=$request->request->get('edit');
            $UnitOfMeasure=$UnitOfMeasureRepository->findOneBy(['id'=>$id]);
            $form = $this->createForm(UnitOfMeasureType::class, $UnitOfMeasure);
            $form->handleRequest($request);
    
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();
    
                return $this->redirectToRoute('unit_of_measure_index');
            }

            $queryBuilder=$UnitOfMeasureRepository->findUnitOfMeasure($request->query->get('search'));
            $data=$paginator->paginate(
                $queryBuilder,
                $request->query->getInt('page',1),
                18
            );
            return $this->render('unit_of_measure/index.html.twig', [
                'UnitOfMeasures' => $data,
                'form' => $form->createView(),
                'edit'=>$id
            ]);

        }
        $UnitOfMeasure = new UnitOfMeasure();
        $form = $this->createForm(UnitOfMeasureType::class, $UnitOfMeasure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($UnitOfMeasure);
            $entityManager->flush();

            return $this->redirectToRoute('unit_of_measure_index');
        }

        $search = $request->query->get('search');
        
        $queryBuilder=$UnitOfMeasureRepository->findUnitOfMeasure($search);
        $data=$paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page',1),
            18
        );
        return $this->render('unit_of_measure/index.html.twig', [
            'UnitOfMeasures' => $data,
            'form' => $form->createView(),
            'edit'=>false
        ]);
        // return $this->render('unit_of_measure/index.html.twig', [
        //     'unit_of_measures' => $unitOfMeasureRepository->findAll(),
        // ]);
    }


    /**
     * @Route("/{id}", name="unit_of_measure_delete", methods={"DELETE"})
     */
    public function delete(Request $request, UnitOfMeasure $unitOfMeasure): Response
    {
        $this->denyAccessUnlessGranted("system_setting");
        if ($this->isCsrfTokenValid('delete'.$unitOfMeasure->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($unitOfMeasure);
            $entityManager->flush();
        }

        return $this->redirectToRoute('unit_of_measure_index');
    }
}
