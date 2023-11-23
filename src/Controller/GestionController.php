<?php

namespace App\Controller;

use App\Entity\Publication;

use App\Repository\PublicationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GestionController extends AbstractController
{
    #[Route('/gestion', name: 'app_gestion')]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('gestion/index.html.twig', [
            'brouillons' => $publicationRepository->Brouillon(),
            'publicationBrouillonMembre' => $publicationRepository->PublicationBrouillonMembre(),
        ]);
    }

    
}
