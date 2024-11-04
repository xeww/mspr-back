<?php

namespace App\Controller;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ConcertController extends AbstractController
{
    #[Route("/concert/new", name: "new_concert")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $concert = new Concert();

        $form = $this->createForm(ConcertType::class, $concert);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute("new_concert");
        }

        return $this->render("concert/new.html.twig", [
            "form" => $form,
        ]);
    }

    #[Route("/concert/list", name: "list_concert")]
    public function list(ConcertRepository $concertRepository): Response
    {
        $all = $concertRepository->findAll();
        return $this->render("concert/list.html.twig", [
            "concerts" => $all,
        ]);
    }

    #[Route("/concert/remove/{id}", name: "remove_concert")]
    public function remove(int $id, ConcertRepository $concertRepository, EntityManagerInterface $entityManager): Response
    {
        $concert = $concertRepository->find($id);
        $entityManager->remove($concert);
        $entityManager->flush();

        return $this->redirectToRoute("list_concert");
    }

    #[Route("/api/concert/get/{id}", name: "get_concert", methods: "GET")]
    public function get(int $id, ConcertRepository $repository, SerializerInterface $serializer): Response
    {
        $entity = $repository->find($id);

        if (!$entity) {
            return new JsonResponse(["error" => "not found"], Response::HTTP_NOT_FOUND);
        }

        $serialized = $serializer->serialize($entity, "json");

        return new Response($serialized, Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }

    #[Route("/api/concert/getall", name: "getall_concert", methods: "GET")]
    public function getAll(ConcertRepository $repository, SerializerInterface $serializer): Response
    {
        $all = $repository->findAll();
        $serialized = $serializer->serialize($all, "json");

        return new Response($serialized, Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }
}
