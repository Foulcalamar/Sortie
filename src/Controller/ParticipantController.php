<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant', name: 'app_participant')]
class ParticipantController extends AbstractController
{
    #[Route('/profile/{id}', name: '_profile')]
    public function profile(int $id, ParticipantRepository $participantRepository, Security $security): Response
    {
        $participant = $participantRepository->find($id);

        $email = $participant?->getEmail();

        $loggedInUser = $security->getUser();
        $loggedInUserId = ($loggedInUser !== null) ? $loggedInUser->getUserIdentifier() : null;

        if ($loggedInUserId === $email) {
            return $this->render('participant/myprofile.html.twig', [
                "participant" => $participant
            ]);
        } else {
            return $this->render('participant/profile.html.twig', [
                "participant" => $participant
            ]);
        }
    }
}
