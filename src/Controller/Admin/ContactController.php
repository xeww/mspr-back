<?php

namespace App\Controller\Admin;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route("/admin/contact", name: "contact_admin_index", methods: ['GET'])]
    public function index(ContactRepository $repository): Response
    {
        return $this->render("admin/contact/contact_messages.html.twig", ["messages" => $repository->findAll()]);
    }

    #[Route("/admin/contact/delete/{id}", name: "contact_admin_delete", methods: ["GET"])]
    public function delete(Contact $entity, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($entity);
        $entityManager->flush();

        return $this->redirectToRoute("contact_admin_index");
    }
}
