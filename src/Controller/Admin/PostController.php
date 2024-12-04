<?php

namespace App\Controller\Admin;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/post", name: "post_admin_")]
class PostController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(PostRepository $repository): Response
    {
        return $this->render("admin/list/list_index.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des posts",
            "identityAttribute" => "title",
            "adminUrl" => $this->generateUrl("post_admin_index"),
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Post $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PostType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entity->setUpdatedOn(new \DateTime());
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("post_admin_index");
        }

        return $this->render("admin/list/list_edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $entity = new Post();
        $entity->setCreatedOn(new \DateTimeImmutable());
        return $this->edit($entity, $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(Post $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("post_admin_index");
    }
}
