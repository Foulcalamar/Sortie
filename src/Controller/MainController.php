<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(
        Request $request,
        SortieRepository $sortieRepository,
        EntityManagerInterface $entityManager,
        EtatRepository $etatRepository,
        Security $security
    ): Response {
        $currentTime = new \DateTime();

        // Fetch the corresponding Etat entities for different states by their IDs
        $enCoursEtat = $etatRepository->find(33);
        $passeeEtat = $etatRepository->find(34);
        $ouvertEtat = $etatRepository->find(31);
        $clotureEtat = $etatRepository->find(32);
        $draftEtat = $etatRepository->find(29);
        $creeEtat = $etatRepository->find(30);
        $sortiesToUpdate = $sortieRepository->findAll();

        // Update 'etat' property based on conditions
        foreach ($sortiesToUpdate as $sortie) {
            // Your logic for updating Sortie entities based on Etat
            // Example:
            $this->updateSortieEtat($sortie, $security, $currentTime, $enCoursEtat, $passeeEtat, $ouvertEtat, $clotureEtat, $draftEtat, $creeEtat);
        }

        // Persist changes
        $entityManager->flush();

        // Fetch the updated 'sorties' after the status update
        $sorties = $sortieRepository->findAll();

        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
        ]);
    }

    // Helper function to update Sortie entity's Etat
    private function updateSortieEtat($sortie, $security, \DateTime $currentTime, ?Etat $enCoursEtat, ?Etat $passeeEtat, ?Etat $ouvertEtat, ?Etat $clotureEtat, ?Etat $draftEtat, ?Etat $creeEtat): void
    {
        $loggedInUser = $security->getUser();
        $loggedInUserId = ($loggedInUser !== null) ? $loggedInUser->getUserIdentifier() : null;
        $dateHeureDebut = $sortie->getDateHeureDebut();
        $dateHeureFin = date_add(clone $dateHeureDebut, date_interval_create_from_date_string($sortie->getDuree() . ' minutes'));
        $participantTotal = count($sortie->getParticipantsInscrits());
        $spaceTotal = $sortie->getNbInscriptionsMax();
        $organiseur = $sortie->getParticipantOrganisateur();

        if ($dateHeureFin < $currentTime) {
            $sortie->setEtat($passeeEtat);
        } elseif ($dateHeureDebut <= $currentTime && $dateHeureFin > $currentTime) {
            $sortie->setEtat($enCoursEtat);
        } elseif ($participantTotal >= $spaceTotal) {
            $sortie->setEtat($clotureEtat);
        } elseif ($dateHeureDebut > $currentTime and $loggedInUser == $organiseur) {
        } elseif ($dateHeureDebut > $currentTime and $loggedInUser == $organiseur) {
        } elseif ($dateHeureDebut > $currentTime) {
            $sortie->setEtat($ouvertEtat);
        }

    }



}
