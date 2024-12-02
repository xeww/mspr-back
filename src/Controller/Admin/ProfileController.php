<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Form\ProfileType;
use App\Repository\AdminUserRepository;
use App\Utils\PasswordUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class ProfileController extends AbstractController
{
    #[Route("/admin/profile", name: "profile_admin", methods: ["GET", "POST"])]
    public function index(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, AdminUserRepository $repository): Response
    {
        $oldUser = $this->getUser();
        if (!$oldUser instanceof AdminUser) {
            throw $this->createAccessDeniedException();
        }

        $form = $this->createForm(ProfileType::class, $oldUser);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $newPassword = $form->get("newPassword")->getData();

            if(!$user instanceof AdminUser) {
                $this->addFlash("error", "Utilisateur mal-formé.");
                return $this->redirectToRoute("profile_admin");
            }

            if(is_string($newPassword)) {
                $passwordCheck = PasswordUtils::isPasswordValid($newPassword);
                if($passwordCheck["valid"] === false) {
                    $this->addFlash("error", $passwordCheck["message"]);
                    return $this->redirectToRoute("profile_admin");
                }
            }

            if(count($repository->findBy(["username" => $user->getUsername()])) !== 0 && $user->getUsername() !== $oldUser->getUsername()) {
                $this->addFlash("error", "Ce nom d'utilisateur est déjà utilisé.");
                return $this->redirectToRoute("profile_admin");
            }

            if($newPassword !== null) {
                $user->setPassword($hasher->hashPassword($user, $newPassword));
            }

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("home_admin");
        }

        return $this->render("admin/profile/profile.html.twig", ["form" => $form]);
    }
}
