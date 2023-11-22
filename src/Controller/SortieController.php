<?php

namespace App\Controller;

use App\Form\SortieCreateFormType;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'app_sortie')]
class SortieController extends AbstractController
{
    #[Route('/details/{id<\d+>}', name: '_details')]
    public function sortie(int $id, ParticipantRepository $participantRepository, SortieRepository $sortieRepository): Response
    {
        $sortie = $sortieRepository->find($id);
        $participantSortie = $participantRepository->findAll();

        return $this->render('sortie/details.html.twig', [
            "sortie" => $sortie,
            "participant" =>$participantSortie
        ]);
    }

    #[Route('/create', name: '_create')]
    public function create(Request $request, VilleRepository $villeRepository): Response
    {
        $form = $this->createForm(SortieCreateFormType::class);

        return $this->render('sortie/create.html.twig', [
            'form' => $form->createView(),
            'villes' => $villeRepository->findAll(), // Fetch all Ville options
        ]);
    }

    #[Route('/get-lieux-for-ville/{villeId}', name: 'get_lieux_for_ville')]
    public function getLieuxForVille(int $villeId, LieuRepository $lieuRepository): JsonResponse
    {
        $lieux = $lieuRepository->findBy(['ville' => $villeId]);

        $options = [];
        foreach ($lieux as $lieu) {
            $options[$lieu->getId()] = $lieu->getNom();
        }

        return new JsonResponse($options);
    }

    #[Route('/get-codePostal-for-ville/{villeId}', name: 'get_codePostal_for_ville')]
    public function getCodePostalForVille(int $villeId, VilleRepository $villeRepository): JsonResponse
    {
        $ville = $villeRepository->find($villeId);
        $codePostal = $ville ? $ville->getCodePostal() : '';

        return new JsonResponse($codePostal);
    }

    #[Route('/get-rue-for-lieu/{lieuId}', name: 'get_rue_for_lieu')]
    public function getRueForLieu(int $lieuId, LieuRepository $lieuRepository): JsonResponse
    {
        $lieu = $lieuRepository->find($lieuId);
        $rue = $lieu ? $lieu->getRue() : '';

        return new JsonResponse($rue);
    }

    #[Route('/get-latitude-for-lieu/{lieuId}', name: 'get_latitude_for_lieu')]
    public function getLatitudeForLieu(int $lieuId, LieuRepository $lieuRepository): JsonResponse
    {
        $lieu = $lieuRepository->find($lieuId);
        $latitude = $lieu ? $lieu->getLatitude() : '';

        return new JsonResponse($latitude);
    }

    #[Route('/get-longitude-for-lieu/{lieuId}', name: 'get_longitude_for_lieu')]
    public function getLongitudeForLieu(int $lieuId, LieuRepository $lieuRepository): JsonResponse
    {
        $lieu = $lieuRepository->find($lieuId);
        $longitude = $lieu ? $lieu->getLongitude() : '';

        return new JsonResponse($longitude);
    }


}
