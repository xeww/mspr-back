<?php

namespace App\Controller\Admin;

use App\Entity\Partner;
use App\Form\PartnerType;
use App\Repository\PartnerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route("/admin/partner", name: "partner_admin_")]
class PartnerController extends AbstractController
{
    #[Route("/", name: "index", methods: ["GET"])]
    public function index(PartnerRepository $repository): Response
    {
        return $this->render("admin/list.html.twig", [
            "entities" => $repository->findAll(),
            "title" => "Liste des partenaires",
            "identityAttribute" => "name",
            "adminUrl" => $this->generateUrl("partner_admin_index"),
            "apiUrl" => $this->generateUrl("partner_api_index")
        ]);
    }

    #[Route("/edit/{id}", name: "edit", methods: ["GET", "POST"])]
    public function edit(Partner $entity, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PartnerType::class, $entity);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $form->getData();
            $entityManager->persist($entity);
            $entityManager->flush();

            return $this->redirectToRoute("partner_admin_index");
        }

        return $this->render("admin/edit.html.twig", [
            "entity" => $entity,
            "form" => $form
        ]);
    }

    #[Route("/create", name: "create", methods: ["GET", "POST"])]
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        return $this->edit(new Partner(), $request, $entityManager);
    }

    #[Route("/delete/{id}", name: "delete", methods: ["GET"])]
    public function delete(Partner $entity, EntityManagerInterface $entityManager) : Response {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("partner_admin_index");
    }
}
