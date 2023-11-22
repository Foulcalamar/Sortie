<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Form\SortieCreateFormType;
use App\Repository\LieuRepository;
use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
    public function create(Request $request, VilleRepository $villeRepository, Security $security, EntityManagerInterface $entityManager): Response {
        $user = $security->getUser();
        $form = $this->createForm(SortieCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $etat = $entityManager->getRepository(Etat::class)->find(29);
            if (!$etat) {
                throw $this->createNotFoundException('Default Etat not found');
            }

            $lieu = $formData->getLieu();

            $sortie = new Sortie();
            $sortie->setNom($formData->getNom());
            $sortie->setDateHeureDebut($formData->getDateHeureDebut());
            $sortie->setDateLimiteInscription($formData->getDateLimiteInscription());
            $sortie->setNbInscriptionsMax($formData->getNbInscriptionsMax());
            $sortie->setDuree($formData->getDuree());
            $sortie->setInfosSortie($formData->getInfosSortie());
            $sortie->setCampus($formData->getCampus());
            $sortie->setLieu($lieu);
            $sortie->setParticipantOrganisateur($user);
            $sortie->setEtat($etat);

            $entityManager->persist($sortie);
            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('sortie/create.html.twig', [
            'form' => $form->createView(),
            'villes' => $villeRepository->findAll(),
            'organiseur_default' => $user->getId(),
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
