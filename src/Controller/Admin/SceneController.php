<?php

namespace App\Controller\Admin;

use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/scene", name: "scene_admin_")]
class SceneController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(SceneRepository $repository): Response
    {
        return $this->render("admin/list/list.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des scènes",
            "identityAttribute" => "name",
            "adminUrl" => $this->generateUrl("scene_admin_index"),
            "apiUrl" => $this->generateUrl("scene_api_index")
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Scene $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SceneType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("scene_admin_index");
        }

        return $this->render("admin/list/edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(new Scene(), $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(Scene $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("scene_admin_index");
    }
}
