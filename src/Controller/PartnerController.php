<?php

namespace App\Controller;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class PartnerController extends AbstractController
{
    #[Route("/partner/new", name: "new_partner")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $partner = new Partner();

        $form = $this->createForm(PartnerType::class, $partner);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $partner = $form->getData();

            $entityManager->persist($partner);
            $entityManager->flush();

            return $this->redirectToRoute("new_partner");
        }

        return $this->render("partner/new.html.twig", [
            "form" => $form,
        ]);
    }

    #[Route("/partner/list", name: "list_partner")]
    public function list(PartnerRepository $partnerRepository): Response
    {
        $all = $partnerRepository->findAll();
        return $this->render("partner/list.html.twig", [
            "partners" => $all,
        ]);
    }

    #[Route("/partner/remove/{id}", name: "remove_partner")]
    public function remove(int $id, PartnerRepository $partnerRepository, EntityManagerInterface $entityManager): Response
    {
        $partner = $partnerRepository->find($id);
        $entityManager->remove($partner);
        $entityManager->flush();

        return $this->redirectToRoute("list_partner");
    }

    #[Route("/api/partner/get/{id}", name: "get_partner", methods: "GET")]
    public function get(int $id, PartnerRepository $repository, SerializerInterface $serializer) : Response {
        $entity = $repository->find($id);

        if(!$entity) {
            return new JsonResponse(["error" => "not found"], Response::HTTP_NOT_FOUND);
        }

        $serialized = $serializer->serialize($entity, "json");

        return new Response($serialized, Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }

    #[Route("/api/partner/getall", name: "getall_partner", methods: "GET")]
    public function getAll(PartnerRepository $repository, SerializerInterface $serializer): Response {
        $all = $repository->findAll();
        $serialized = $serializer->serialize($all, "json");

        return new Response($serialized, Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }
}
