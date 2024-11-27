<?php

namespace App\Controller\Admin;

use App\Entity\Concert;
use App\Form\ConcertType;
use App\Repository\ConcertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/concert", name: "concert_admin_")]
class ConcertController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(ConcertRepository $repository): Response
    {
        return $this->render("admin/list.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des concerts",
            "identityAttribute" => "id",
            "adminUrl" => $this->generateUrl("concert_admin_index"),
            "apiUrl" => $this->generateUrl("concert_api_index")
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Concert $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConcertType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("concert_admin_index");
        }

        return $this->render("admin/edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(new Concert(), $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(Concert $entity, EntityManagerInterface $entityManager) : Response {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("concert_admin_index");
    }
}
