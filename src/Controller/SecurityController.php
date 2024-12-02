<?php

namespace App\Controller;

use App\Entity\AdminUser;
use App\Repository\AdminUserRepository;
use App\Utils\PasswordUtils;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
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
        throw new \LogicException();
    }

    /**
     * Super-admin registration
     */
    #[Route(path: "/register", name: "security_register")]
    public function register(Request $request, AdminUserRepository $repository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $hasher, CsrfTokenManagerInterface $csrfTokenManager): Response
    {
        if (count($repository->findAll()) !== 0) {
            return $this->redirectToRoute("home_admin");
        }

        if ($request->isMethod("POST")) {
            $csrfToken = $request->request->get('_csrf_token');
            if (!$csrfTokenManager->isTokenValid(new CsrfToken("register", $csrfToken))) {
                throw new \Exception("Token CSRF invalide");
            }

            $username = $request->request->get("_username");
            $password = $request->request->get("_password");
            $firstName = $request->request->get("_first-name");
            $lastName = $request->request->get("_last-name");

            if (empty($username) || empty($password) || empty($firstName) || empty($lastName)) {
                $this->addFlash("error", "Merci de remplir tous les champs.");
                return $this->redirectToRoute("security_register");
            }

            if (count($repository->findBy(["username" => $username])) !== 0) {
                $this->addFlash("error", "Ce nom d'utilisateur est déjà utilisé.");
                return $this->redirectToRoute("security_register");
            }

            $passwordCheck = PasswordUtils::isPasswordValid($password);
            if ($passwordCheck["valid"] === false) {
                $this->addFlash("error", $passwordCheck["message"]);
                return $this->redirectToRoute("security_register");
            }

            $user = new AdminUser();

            $user->setUsername($username);
            $user->setFirstName($firstName);
            $user->setLastName($lastName);
            $user->setPassword($hasher->hashPassword($user, $password));
            $user->setRoles(["ROLE_SUPER_ADMIN"]);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("security_login");
        }

        return $this->render("security/register.html.twig");
    }
}
