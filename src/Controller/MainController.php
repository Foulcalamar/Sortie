<?php

namespace App\Controller;

use App\Repository\ParticipantRepository;
use App\Repository\SortieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Security $security, Request $request, SortieRepository $sortieRepository): Response
    {
        $sorties = $sortieRepository->findAll();
        $loggedInUser = $security->getUser();
        $loggedInUserId = ($loggedInUser !== null) ? $loggedInUser->getId() : null;

        return $this->render('main/index.html.twig', [
            'sorties' => $sorties,
            'user' => $loggedInUserId,
        ]);
    }
}
