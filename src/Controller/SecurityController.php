<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\This;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
class SecurityController extends AbstractController
{
    /**
     * @Route ("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
         if ($this->getUser()) {
             return $this->redirectToRoute('target_path');
         }

         $lastUsername = $authenticationUtils->getLastUsername();

         return $this->render('security/login.html.twig', [
             'last_username' => $lastUsername
         ]);
    }

    /**
     * @Route ("logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new \LogicException();
    }
}