<?php

namespace App\Controller;

use App\Entity\Scene;
use App\Form\SceneType;
use App\Repository\SceneRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SceneController extends AbstractController
{
    #[Route("/scene/new", name: "new_scene")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $scene = new Scene();

        $form = $this->createForm(SceneType::class, $scene);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $scene = $form->getData();

            $entityManager->persist($scene);
            $entityManager->flush();

            return $this->redirectToRoute("new_scene");
        }

        return $this->render("scene/new.html.twig", [
            "form" => $form,
        ]);
    }

    #[Route("/scene/list", name: "list_scene")]
    public function list(SceneRepository $sceneRepository): Response
    {
        $all = $sceneRepository->findAll();
        return $this->render("scene/list.html.twig", [
            "scenes" => $all,
        ]);
    }

    #[Route("/scene/remove/{id}", name: "remove_scene")]
    public function remove(int $id, SceneRepository $sceneRepository, EntityManagerInterface $entityManager): Response
    {
        $scene = $sceneRepository->find($id);
        $entityManager->remove($scene);
        $entityManager->flush();

        return $this->redirectToRoute("list_scene");
    }
}
