<?php

namespace App\Controller\API;

use App\Entity\Concert;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/concert", name: "concert_api_")]
class ConcertController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(ConcertRepository $repository): Response
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "get", methods: ["GET"])]
    public function get(Concert $entity): Response
    {
        return $this->json($entity, Response::HTTP_OK);
    }
}
