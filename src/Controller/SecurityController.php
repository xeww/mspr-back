<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Repository\AdminUserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    #[Route(path: "/login", name: "security_login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render("security/login.html.twig", [
            "last_username" => $lastUsername,
            "error" => $error,
        ]);
    }

    #[Route(path: "/logout", name: "security_logout")]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * Super-admin registration
     */
    #[Route(path: "/register", name: "security_register")]
    public function register(Request $request, AdminUserRepository $repository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher): Response
    {
        if (count($repository->findAll()) !== 0) {
            return $this->redirectToRoute("home_admin");
        }

        if ($request->isMethod("POST")) {
            //TODO: add checks
            $user = new AdminUser();

            $user->setUsername($request->request->get("_username"));
            $user->setPassword($hasher->hashPassword($user, $request->request->get("_password")));
            $user->setRoles(["ROLE_SUPER_ADMIN"]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("security_login");
        }

        return $this->render("security/register.html.twig");
    }
}
