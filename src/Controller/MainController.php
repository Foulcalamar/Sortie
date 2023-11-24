<?php

namespace App\Controller;

use App\Entity\Etat;
use App\Repository\EtatRepository;
use App\Repository\SortieRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(SortieRepository $sortieRepository, EntityManagerInterface $entityManager, EtatRepository $etatRepository, Security $security): Response {

        $currentTime = new DateTime();

        $enCoursEtat = $etatRepository->find(33);
        $passeeEtat = $etatRepository->find(34);
        $ouvertEtat = $etatRepository->find(31);
        $clotureEtat = $etatRepository->find(32);
        $draftEtat = $etatRepository->find(29);
        $fermerEtat = $etatRepository->find(30);
        $annuleEtat = $etatRepository->find(35);
        $sortiesToUpdate = $sortieRepository->findAll();

        foreach ($sortiesToUpdate as $sortie) {
            $this->updateSortieEtat($sortie, $security, $currentTime, $enCoursEtat, $passeeEtat, $ouvertEtat, $clotureEtat, $draftEtat, $fermerEtat, $annuleEtat);
        }

        $entityManager->flush();

        $sorties = $sortieRepository->findAll();

        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
        ]);
    }

    private function updateSortieEtat($sortie, $security, DateTime $currentTime, ?Etat $enCoursEtat, ?Etat $passeeEtat, ?Etat $ouvertEtat, ?Etat $clotureEtat, ?Etat $draftEtat, ?Etat $fermerEtat, ?Etat $annuleEtat): void
    {
        $security->getUser();

        $dateHeureDebut = $sortie->getDateHeureDebut();
        $dateFermeInscription = $sortie->getDateLimiteInscription();
        $dateHeureFin = date_add(clone $dateHeureDebut, date_interval_create_from_date_string($sortie->getDuree() . ' minutes'));
        $participantTotal = count($sortie->getParticipantsInscrits());
        $spaceTotal = $sortie->getNbInscriptionsMax();

        $currentEtat = $sortie->getEtat();

        if ( $currentEtat == $draftEtat){
            $sortie->setEtat($draftEtat);
        } elseif ( $currentEtat == $annuleEtat){
            $sortie->setEtat($annuleEtat);
        }elseif ($dateHeureDebut < $currentTime and  $currentTime < $dateFermeInscription) {
            $sortie->setEtat($fermerEtat);
        } elseif ($dateHeureFin < $currentTime) {
            $sortie->setEtat($passeeEtat);
        } elseif ($dateHeureDebut <= $currentTime && $dateHeureFin > $currentTime) {
            $sortie->setEtat($enCoursEtat);
        } elseif ($participantTotal >= $spaceTotal) {
            $sortie->setEtat($clotureEtat);
        } elseif ($dateHeureDebut > $currentTime) {
            $sortie->setEtat($ouvertEtat);
        }

        //} elseif ($dateHeureDebut > $currentTime and $loggedInUser == $organiseur) {

    }







}
