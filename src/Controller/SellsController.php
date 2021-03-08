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

    
}
