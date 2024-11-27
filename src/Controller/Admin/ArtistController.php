<?php

namespace App\Controller\Admin;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/artist", name: "artist_admin_")]
class ArtistController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(ArtistRepository $repository): Response
    {
        return $this->render("admin/list.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des artistes",
            "identityAttribute" => "name",
            "adminUrl" => $this->generateUrl("artist_admin_index"),
            "apiUrl" => $this->generateUrl("artist_api_index")
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Artist $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArtistType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("artist_admin_index");
        }

        return $this->render("admin/edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(new Artist(), $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(Artist $entity, EntityManagerInterface $entityManager) : Response {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("artist_admin_index");
    }
}
