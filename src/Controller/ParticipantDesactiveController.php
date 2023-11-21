<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantDesactiveController extends AbstractController
{
    #[Route('/participant-desactive', name: 'app_participant_desactive')]
    public function index(): Response
    {
        return $this->render('participant_desactive/index.html.twig', [
            'controller_name' => 'ParticipantDesactiveController',
        ]);
    }
}
