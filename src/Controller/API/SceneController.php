<?php

namespace App\Controller\API;

use App\Entity\Scene;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/scene", name: "scene_api_")]
class SceneController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(SceneRepository $repository): Response
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "get", methods: ["GET"])]
    public function get(Scene $entity): Response
    {
        return $this->json($entity, Response::HTTP_OK);
    }

    #[Route("/{id}", name: "delete", methods: ["DELETE"])]
    public function delete(Scene $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->json(["message" => "Entity deleted successfully"], Response::HTTP_OK);
    }
}
