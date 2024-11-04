<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ArtistController extends AbstractController
{
    #[Route("/artist/new", name: "new_artist")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute("new_artist");
        }

        return $this->render("artist/new.html.twig", [
            "form" => $form,
        ]);
    }

    #[Route("/artist/list", name: "list_artist")]
    public function list(ArtistRepository $artistRepository): Response
    {
        $all = $artistRepository->findAll();
        return $this->render("artist/list.html.twig", [
            "artists" => $all,
        ]);
    }

    #[Route("/artist/remove/{id}", name: "remove_artist")]
    public function remove(int $id, ArtistRepository $artistRepository, EntityManagerInterface $entityManager): Response
    {
        $artist = $artistRepository->find($id);
        $entityManager->remove($artist);
        $entityManager->flush();

        return $this->redirectToRoute("list_artist");
    }

    #[Route("/api/artist/get/{id}", name: "get_artist", methods: "GET")]
    public function get(int $id, ArtistRepository $repository, SerializerInterface $serializer): Response
    {
        $entity = $repository->find($id);

        if (!$entity) {
            return new JsonResponse(["error" => "not found"], Response::HTTP_NOT_FOUND);
        }

        $serialized = $serializer->serialize($entity, "json");

        return new Response($serialized, Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }

    #[Route("/api/artist/getall", name: "getall_artist", methods: "GET")]
    public function getAll(ArtistRepository $repository, SerializerInterface $serializer): Response
    {
        $all = $repository->findAll();
        $serialized = $serializer->serialize($all, "json");

        return new Response($serialized, Response::HTTP_OK, ["Content-Type" => "application/json"]);
    }
}
