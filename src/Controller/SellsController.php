<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\SellsList;
/**
 * @Route("/sells")
 */

class SellsController extends AbstractController
{
    /**
     * @Route("/sells", name="sells")
     */
    public function index(): Response
    {
        return $this->render('sells/index.html.twig', [
            'controller_name' => 'SellsController',
        ]);
    }

    /**
     * @Route("/{id}", name="sells_list_delete", methods={"DELETE"})
     */
    public function delete(Request $request, SellsList $sells): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sells->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($sells);
            $entityManager->flush();
        }

        return $this->redirectToRoute('goods_delivery_index');
    }
}
