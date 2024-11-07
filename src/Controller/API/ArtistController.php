<?php

namespace App\Controller\API;

use App\Entity\Artist;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/artist", name: "artist_api_")]
class ArtistController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(ArtistRepository $repository): Response
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "get", methods: ["GET"])]
    public function get(Artist $entity): Response
    {
        return $this->json($entity, Response::HTTP_OK);
    }

    #[Route("/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(Artist $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->json(["message" => "Entity deleted successfully"], Response::HTTP_OK);
    }
}
