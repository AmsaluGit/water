<?php

namespace App\Controller;

use App\Entity\ConsumptionRequest;
use App\Form\ConsumptionRequestType;
use App\Repository\ConsumptionRequestRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @Route("/consumption")
 */
class ConsumptionRequestController extends AbstractController
{
    /**
     * @Route("/", name="consumption_index", methods={"GET"})
     */
    public function index(ConsumptionRequestRepository $consumptionRequestRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $queryBuilder=$consumptionRequestRepository->findRequester($request->query->get('search'));
                 $data=$paginator->paginate(
                 $queryBuilder,
                 $request->query->getInt('page',1),
               18
            );
            return $this->render('consumption_request/index.html.twig', [
                'consumption_requests' => $data,
            ]);
    }

    }
