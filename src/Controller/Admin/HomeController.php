<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    #[Route("/", name: "default_admin")]
    public function index(): Response
    {
        return $this->redirectToRoute("home_admin");
    }

    #[Route("/admin", name: "home_admin")]
    public function home(): Response
    {
        return $this->render("admin/home.html.twig");
    }
}
