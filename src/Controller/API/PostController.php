<?php

namespace App\Controller\API;

use App\Entity\Post;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/post", name: "post_api_")]
class PostController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(PostRepository $repository): Response
    {
        return $this->json($repository->getAllOrderedByDate(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "get", methods: ["GET"])]
    public function get(Post $entity): Response
    {
        return $this->json($entity, Response::HTTP_OK);
    }
}
