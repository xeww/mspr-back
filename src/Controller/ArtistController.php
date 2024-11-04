<?php

namespace App\Controller;

use App\Entity\Artist;
use App\Form\ArtistType;
use App\Repository\ArtistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArtistController extends AbstractController
{
    #[Route("/artist/new", name: "new_artist")]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $artist = new Artist();

        $form = $this->createForm(ArtistType::class, $artist);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $artist = $form->getData();

            $entityManager->persist($artist);
            $entityManager->flush();

            return $this->redirectToRoute("new_artist");
        }

        return $this->render("artist/new.html.twig", [
            "form" => $form,
        ]);
    }

    #[Route("/artist/list", name: "list_artist")]
    public function list(ArtistRepository $artistRepository): Response
    {
        $all = $artistRepository->findAll();
        return $this->render("artist/list.html.twig", [
            "artists" => $all,
        ]);
    }

    #[Route("/artist/remove/{id}", name: "remove_artist")]
    public function remove(int $id, ArtistRepository $artistRepository, EntityManagerInterface $entityManager): Response
    {
        $artist = $artistRepository->find($id);
        $entityManager->remove($artist);
        $entityManager->flush();

        return $this->redirectToRoute("list_artist");
    }
}
