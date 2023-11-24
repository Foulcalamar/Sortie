<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DesactiverSupprimerParticipantsController extends AbstractController
{

    #[Route('/admin/desactiver-supprimer-participants', name: 'app_desactiver_supprimer_participants')]
    public function index(Security $security,ParticipantRepository $participantRepository): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_main');
        }

        $participantslist = $participantRepository->findAll();
        return $this->render('desactiver_supprimer_participants/index.html.twig', [
            'controller_name' => 'DesactiverSupprimerParticipantsController',
            'participants' => $participantslist,
        ]);
    }

    #[Route('/admin/desactiver-supprimer-participants/supprimer/{id}', name: "participant_supprimer")]
    public function supprimer(Security $security,EntityManagerInterface $entityManager, Participant $participant): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_main');
        }

        $entityManager->remove($participant);
        $entityManager->flush();

        $this->addFlash('success', 'Le participant ' . $participant->getEmail() . ' a été supprimé avec succès !');

        return $this->redirectToRoute('app_desactiver_supprimer_participants');
    }

    #[Route('/admin/desactiver-supprimer-participants/desactiver/{id}', name: "participant_desactiver")]
    public function desactiver(Security $security,EntityManagerInterface $entityManager, Participant $participant): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_main');
        }

        $participant->setActif(false);
        $entityManager->flush();

        $this->addFlash('success', 'Le participant ' . $participant->getEmail() . ' a été désactivé avec succès !');

        return $this->redirectToRoute('app_desactiver_supprimer_participants');
    }

    #[Route('/admin/desactiver-supprimer-participants/activer/{id}', name: "participant_activer")]
    public function activer(Security $security, EntityManagerInterface $entityManager, Participant $participant): Response
    {
        if (!$security->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('app_main');
        }

        $participant->setActif(true);
        $entityManager->flush();

        $this->addFlash('success', 'Le participant ' . $participant->getEmail() . ' a été activé avec succès !');

        return $this->redirectToRoute('app_desactiver_supprimer_participants');
    }


}
