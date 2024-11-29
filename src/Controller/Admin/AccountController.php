<?php

namespace App\Controller\Admin;

use App\Entity\AdminUser;
use App\Form\AccountType;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class AccountController extends AbstractController
{
    #[Route("/admin/account", name: "account_admin_index")]
    public function index(AdminUserRepository $repository) : Response {
        return $this->render("admin/account/account_index.html.twig", ["accounts" => $repository->findAll()]);
    }

    #[Route("/admin/account/edit/{id}", name: "account_admin_edit")]
    public function edit(AdminUser $user, Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, $creating = false) : Response {
        $form = $this->createForm(AccountType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            if($user instanceof AdminUser) {
                $plainPassword = $form->get("plainPassword")->getData();
                $isSuperAdmin = $form->get("isSuperAdmin")->getData();

                if($plainPassword === null) {
                    if($creating) {
                        return $this->redirectToRoute("account_admin_index");
                    }
                }else{
                    $user->setPassword($hasher->hashPassword($user, $plainPassword));
                }

                $isSuperAdmin ? $user->setRoles(["ROLE_SUPER_ADMIN"]) : $user->setRoles([]);

                $entityManager->persist($user);
                $entityManager->flush();

                return $this->redirectToRoute("account_admin_index");
            }
        }

        return $this->render("admin/account/account_edit.html.twig", ["form" => $form, "creating" => $creating]);
    }

    #[Route("/admin/account/create", name: "account_admin_create")]
    public function create(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher) : Response {
        return $this->edit(new AdminUser(), $request, $entityManager, $hasher, true);
    }

    #[Route("/admin/account/delete/{id}", name: "account_admin_delete")]
    public function delete(AdminUser $user, EntityManagerInterface $entityManager): Response {
        if(!$user->hasRole("ROLE_SUPER_ADMIN")) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute("account_admin_index");
    }
}