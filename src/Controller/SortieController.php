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
            "participant" => $participantSortie
        ]);
    }

    #[Route('/edit/{id<\d+>}', name: '_edit')]
    public function edit(
        int $id,
        Security $security,
        VilleRepository $villeRepository,
        ParticipantRepository $participantRepository,
        SortieRepository $sortieRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();
        $sortie = $sortieRepository->find($id);

        // Create the form with the SortieCreateFormType and populate it with the Sortie entity's data
        $form = $this->createForm(SortieCreateFormType::class, $sortie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Update the existing Sortie entity with the form data
            $sortie = $form->getData();

            $etat = $entityManager->getRepository(Etat::class);

            // Update the participant organisateur (if needed)
            $sortie->setParticipantOrganisateur($user);

            if ($request->request->has('modifier')) {
                $sortie->setEtat($etat->find(29)); // Save as Draft (state = 29)
            } elseif ($request->request->has('publier')) {
                $sortie->setEtat($etat->find(31)); // Publish (state = 31)
            } elseif ($request->request->has('annuler')) {
                $sortie->setEtat($etat->find(35)); // Publish (state = 31)
            }

            $entityManager->flush();

            return $this->redirectToRoute('app_main');
        }

        return $this->render('sortie/edit.html.twig', [
            "sortie" => $sortie,
            'form' => $form->createView(),
            'villes' => $villeRepository->findAll(),
            'organiseur_default' => $user->getId(),
        ]);
    }

    #[Route('/inscrit/{id<\d+>}', name: '_inscrit')]
    public function inscrit(
        int $id,
        Security $security,
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();
        $sortie = $sortieRepository->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException('Sortie not found');
        }

        // Check if the user is already registered for the sortie
        if ($sortie->getParticipantsInscrits()->contains($user)) {
            // Redirect or show a message indicating the user is already registered
            return $this->redirectToRoute('app_main');
        }

        // If the user is not registered, add the user to the sortie
        $sortie->addParticipantsInscrit($user);

        // Persist changes
        $entityManager->flush();

        // Redirect or show a success message
        return $this->redirectToRoute('app_main');
    }

    #[Route('/desist/{id<\d+>}', name: '_desist')]
    public function desist(
        int $id,
        Security $security,
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager
    ): Response {
        $user = $security->getUser();
        $sortie = $sortieRepository->find($id);

        if (!$sortie) {
            throw $this->createNotFoundException('Sortie not found');
        }

        // Check if the user is registered for the sortie
        if ($sortie->getParticipantsInscrits()->contains($user)) {
            // Remove the user from the sortie
            $sortie->removeParticipantsInscrit($user);

            // Persist changes
            $entityManager->flush();

            // Redirect or show a success message
            return $this->redirectToRoute('app_main');
        }

        // Redirect or show a message indicating the user is not registered
        return $this->redirectToRoute('error_route');
    }


    #[Route('/create', name: '_create')]
    public function create(Request $request, VilleRepository $villeRepository, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $security->getUser();
        $form = $this->createForm(SortieCreateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();

            $etat = $entityManager->getRepository(Etat::class);

            $sortie = new Sortie();
            $sortie->setNom($formData->getNom());
            $sortie->setDateHeureDebut($formData->getDateHeureDebut());
            $sortie->setDateLimiteInscription($formData->getDateLimiteInscription());
            $sortie->setNbInscriptionsMax($formData->getNbInscriptionsMax());
            $sortie->setDuree($formData->getDuree());
            $sortie->setInfosSortie($formData->getInfosSortie());
            $sortie->setCampus($formData->getCampus());
            $sortie->setLieu($formData->getLieu());
            $sortie->setParticipantOrganisateur($user);

            if ($request->request->has('draft')) {
                $sortie->setEtat($etat->find(29)); // Save as Draft (state = 29)
            } elseif ($request->request->has('publish')) {
                $sortie->setEtat($etat->find(31)); // Publish (state = 31)
            }

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
