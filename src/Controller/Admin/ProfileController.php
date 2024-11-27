<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Form\ProfileType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route("/admin/profile", name: "profile_admin", methods: ["GET", "POST"])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        $user = $this->getUser();
        if (!$user instanceof AdminUser) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $newPassword = $form->get("newPassword")->getData();

            $user->setPassword($hasher->hashPassword($user, $newPassword));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("home_admin");
        }

        return $this->render("admin/profile.html.twig", ["form" => $form]);
    }
}
