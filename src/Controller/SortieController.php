<?php

namespace App\Controller;


use App\Repository\LieuRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{
    #[Route('/details/{id<\d+>}', name: '_details')]
    public function sortie(int $id, SortieRepository $sortieRepository, Security $security): Response
    {
        $sortie = $sortieRepository->find($id);

        return $this->render('sortie/details.html.twig', [
            "sortie" => $sortie
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(LieuRepository $lieuRepository, VilleRepository $villeRepository, SortieRepository $sortieRepository, Security $security): Response
    {
        $ville=$villeRepository->findAll();
        $lieu=$lieuRepository->findAll();
        return $this->render('sortie/create.html.twig', [
            "ville" => $ville,
            "lieu" => $lieu
        ]);
    }
}