<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DesactiverSupprimerParticipantsController extends AbstractController
{
    #[Route('/admin/desactiver-supprimer-participants', name: 'app_desactiver_supprimer_participants')]
    public function index(ParticipantRepository $participantRepository): Response
    {
        $participantslist = $participantRepository->findAll();
        return $this->render('desactiver_supprimer_participants/index.html.twig', [
            'controller_name' => 'DesactiverSupprimerParticipantsController',
            'participants' => $participantslist,
        ]);
    }

    #[Route('/admin/desactiver-supprimer-participants/supprimer/{id}', name: "participant_supprimer")]
    public function supprimer(EntityManagerInterface $entityManager, Participant $participant): Response
    {
        $entityManager->remove($participant);
        $entityManager->flush();

        $this->addFlash('success', 'Le participant a été supprimé avec succès !');

        return $this->redirectToRoute('app_desactiver_supprimer_participants');
    }

}
