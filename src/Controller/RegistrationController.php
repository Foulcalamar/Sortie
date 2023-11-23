<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{


    #[Route('/admin/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Participant();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setMotPasse(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur enregistré avec succès!');
            $this->addFlash('email', $form->get('email')->getData());
            $this->addFlash('pseudo', $form->get('pseudo')->getData());
            $this->addFlash('plainPassword', $form->get('plainPassword')->getData());
            $this->addFlash('nom', $form->get('nom')->getData());
            $this->addFlash('prenom', $form->get('prenom')->getData());
            $this->addFlash('campus', $form->get('campus')->getData());
            $this->addFlash('telephone', $form->get('telephone')->getData());
            $this->addFlash('actif', $form->get('actif')->getData());
            $this->addFlash('administrateur', $form->get('administrateur')->getData());


            return $this->redirectToRoute('app_register');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
