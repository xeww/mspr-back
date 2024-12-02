<?php

namespace App\Controller\Admin;

use App\Entity\Stand;
use App\Form\StandType;
use App\Repository\StandRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/stand", name: "stand_admin_")]
class StandController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(StandRepository $repository): Response
    {
        return $this->render("admin/list/list_index.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des stands",
            "identityAttribute" => "name",
            "adminUrl" => $this->generateUrl("stand_admin_index"),
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Stand $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StandType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("stand_admin_index");
        }

        return $this->render("admin/list/list_edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(new Stand(), $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(Stand $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("stand_admin_index");
    }
}
