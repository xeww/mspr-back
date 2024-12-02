<?php

namespace App\Controller\API;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\RateLimiter\RateLimiterFactory;
use Symfony\Component\Routing\Attribute\Route;

class ContactController extends AbstractController
{
    #[Route("/api/contact", name: "contact", methods: ["POST"])]
    public function send(Request $request, EntityManagerInterface $entityManager, RateLimiterFactory $contactApiLimiter): Response
    {
        $content = json_decode($request->getContent(), true);

        $fullName = $content["fullName"] ?? null;
        $email = $content["email"] ?? null;
        $message = $content["message"] ?? null;

        if ($fullName === null || strlen($fullName) >= 255) {
            return $this->json(["error" => "Le nom/prénom est trop long ou n'est pas renseigné."], Response::HTTP_BAD_REQUEST);
        }

        if ($email === null || strlen($email) >= 255) {
            return $this->json(["error" => "L'email est trop longue ou n'est pas renseignée."], Response::HTTP_BAD_REQUEST);
        }

        if ($message === null || strlen($message) >= 2000) {
            return $this->json(["error" => "Le message est trop long ou n'est pas renseigné."], Response::HTTP_BAD_REQUEST);
        }

        $limiter = $contactApiLimiter->create($request->getClientIp());

        if (false === $limiter->consume(1)->isAccepted()) {
            return $this->json(["error" => "Veuillez attendre quelques minutes avant de renvoyer un formulaire."], Response::HTTP_BAD_REQUEST);
        }

        $contact = new Contact();
        $contact->setSenderFullName($fullName);
        $contact->setSenderEmail($email);
        $contact->setMessage($message);
        $contact->setSentOn(new \DateTime());

        $entityManager->persist($contact);
        $entityManager->flush();

        return $this->json(["success" => "Le formulaire a été envoyé à notre équipe."], Response::HTTP_CREATED);
    }
}
