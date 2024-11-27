<?php

namespace App\Controller\API;

use App\Entity\WC;
use App\Repository\WCRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/wc", name: "wc_api_")]
class WCController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(WCRepository $repository): Response
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "get", methods: ["GET"])]
    public function get(WC $entity): Response
    {
        return $this->json($entity, Response::HTTP_OK);
    }
}
