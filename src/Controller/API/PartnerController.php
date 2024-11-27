<?php

namespace App\Controller\API;

use App\Entity\Partner;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/api/partner", name: "partner_api_")]
class PartnerController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(PartnerRepository $repository): Response
    {
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "get", methods: ["GET"])]
    public function get(Partner $entity): Response
    {
        return $this->json($entity, Response::HTTP_OK);
    }
}
