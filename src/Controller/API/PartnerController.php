<?php

namespace App\Controller\API;

use App\Entity\Partner;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

#[Route("/api/partner", name: "partner_api")]
class PartnerController extends AbstractController
{
    #[Route("/", name: "partner_api_index", methods: ["GET"])]
    public function index(PartnerRepository $repository) :Response{
        return $this->json($repository->findAll(), Response::HTTP_OK);
    }

    #[Route("/{id}", name: "partner_api_get", methods: ["GET"])]
    public function get(Partner $entity):Response {
        return $this->json($entity, Response::HTTP_OK);
    }

    #[Route("/{id}", name: "partner_api_update", methods: ["PUT"])]
    public function update(Partner $entity, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer) : Response {
        $requestData = $request->getContent();
        $updatedEntity = $serializer->deserialize($requestData, Partner::class, "json");
        $from = $serializer->serialize($entity, "json");

        if($updatedEntity->getName()) $entity->setName($updatedEntity->getName());
        if($updatedEntity->getType()) $entity->setType($updatedEntity->getType());
        if($updatedEntity->getImageName()) $entity->setImageName($updatedEntity->getImageName());
        if($updatedEntity->getImageFile()) $entity->setImageFile($updatedEntity->getImageFile());

        $entityManager->flush();

        $to = $serializer->serialize($entity, "json");

        return $this->json([
            "message" => "Entity updated",
            "from" => json_decode($from),
            "to" => json_decode($to)
        ], Response::HTTP_OK);
    }

    #[Route("/{id}", name: "partner_api_delete", methods: ["DELETE"])]
    public function delete(Partner $entity, EntityManagerInterface $entityManager) : Response {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->json(["message" => "Entity deleted successfully"], Response::HTTP_OK);
    }

    #[Route("/create", name: "partner_api_create", methods: ["POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer):Response {
        $requestData = $request->getContent();
        $entity = $serializer->deserialize($requestData, Partner::class, "json");

        if(!$entity->getName() || !$entity->getType() || !$entity->getImageName() || !$entity->getImageUrl()) {
            return $this->json(["error" => "Missing required fields"], Response::HTTP_BAD_REQUEST);
        }

        $entityManager->persist($entity);
        $entityManager->flush();

        $data = $serializer->serialize($entity, "json");

        return $this->json(["message" => "Entity created", "entity" => json_decode($data)], Response::HTTP_CREATED);
    }
}