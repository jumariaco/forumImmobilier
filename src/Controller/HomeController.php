<?php

namespace App\Controller;

use App\Form\RechercheType;
use App\Entity\Publication;
use App\Repository\PublicationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home', methods: ['GET', 'POST'])]
    public function index(PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
        $publications = $publicationRepository->findAll();

        return $this->render('home.html.twig', [
            'publications' => $publications,
        ]);
    }

    #[Route('/recherche', name: 'app_home_recherche', methods: ['GET', 'POST'])]
    public function recherche(PublicationRepository $publicationRepository, UserRepository $userRepository,Request $request, EntityManagerInterface $entityManager): Response
    {
        $publicationResults = [];
        $userResults = [];
    
        if ($request->query->has('keyword')) {
            // Traitez la recherche lorsque le formulaire est soumis
            $keyword = $request->query->get('keyword');
            
            // Récupérez les résultats des publications
            $publicationResults = $entityManager->getRepository(Publication::class)->RecherchePublication($keyword, null, null);
    
            // Récupérez les résultats des profils d'utilisateurs
            $userResults = $userRepository->RechercheUtilisateur($keyword);
        }
    
        return $this->render('recherche.html.twig', [
            'publicationResults' => $publicationResults,
            'userResults' => $userResults,
        ]);

        // $results = [];

        // if ($request->query->has('keyword')) {
        //     // Traitez la recherche lorsque le formulaire est soumis
        //     $keyword = $request->query->get('keyword');
        //     $results = $entityManager->getRepository(Publication::class)->RecherchePublication($keyword, null, null);
        // }

        // return $this->render('recherche.html.twig', [
        //     'results' => $results,
        // ]);
    }

}
