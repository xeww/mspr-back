<?php

namespace App\Controller\Admin;

use App\Entity\WC;
use App\Form\WCType;
use App\Repository\WCRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/wc", name: "wc_admin_")]
class WCController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(WCRepository $repository): Response
    {
        return $this->render("admin/list.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des WC",
            "identityAttribute" => "id",
            "adminUrl" => $this->generateUrl("wc_admin_index"),
            "apiUrl" => $this->generateUrl("wc_api_index")
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(WC $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(WCType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("wc_admin_index");
        }

        return $this->render("admin/edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(new WC(), $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(WC $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("wc_admin_index");
    }
}
