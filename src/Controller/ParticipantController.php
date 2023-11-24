<?php

namespace App\Controller;

use App\Form\UpdateUserFormType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/participant', name: 'app_participant')]
class ParticipantController extends AbstractController
{
    #[Route('/profile/{id<\d+>}', name: '_profile')]
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

    #[Route('/edit/{id<\d+>}', name: '_edit')]
    public function edit(int $id, Request $request, ParticipantRepository $participantRepository, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = $participantRepository->find($id);
        $form = $this->createForm(UpdateUserFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setMotPasse(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('app_participant_profile', ['id' => $id]); // Redirect to profile page after editing
        }

        return $this->render('participant/editprofile.html.twig', [
            'updateUserForm' => $form->createView(),
        ]);
    }
}
