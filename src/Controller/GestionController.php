<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Titre;
use App\Repository\PublicationRepository;
use App\Repository\TitreRepository;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class GestionController extends AbstractController
{
    #[Route('/gestion', name: 'app_gestion')]
    public function index(PublicationRepository $publicationRepository, UserRepository $userRepository, TitreRepository $titreRepository): Response
    {
        
        return $this->render('gestion/index.html.twig', [
            'brouillons' => $publicationRepository->Brouillon(),
            'publicationBrouillonMembre' => $publicationRepository->PublicationBrouillonMembre(),
            'partenairesNonActifs' => $userRepository->FindByPartenaireNonActif(),
            'titres' => $titreRepository->findAll(),

        ]);
    }
    
    #[Route('/{id}/active', name: 'app_profile_active', methods: ['POST'])]
    public function active(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Vérifiez le jeton CSRF
        if ($this->isCsrfTokenValid('active' . $user->getId(), $request->request->get('_token'))) {
            // Modifiez le statut de la publication
            $user->setActif(true);

            // Enregistrez les modifications
            $entityManager->flush();
        }
        $this->addFlash('success', 'Profil activé avec succès');

        return $this->redirectToRoute('app_gestion', [], Response::HTTP_SEE_OTHER);
    }

    
}
