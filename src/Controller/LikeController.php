<?php

namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Like;
use App\Entity\Publication;
use App\Form\LikeType;
use App\Repository\LikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/like')]
class LikeController extends AbstractController
{
    #[Route('/', name: 'app_like_index', methods: ['GET'])]
    public function index(LikeRepository $likeRepository): Response
    {
        return $this->render('like/index.html.twig', [
            'likes' => $likeRepository->findAll(),
        ]);
    }

    // #[Route('/new', name: 'app_like_new', methods: ['GET', 'POST'])]
    // public function new(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $like = new Like();
    //     $form = $this->createForm(LikeType::class, $like);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $entityManager->persist($like);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('like/new.html.twig', [
    //         'like' => $like,
    //         'form' => $form,
    //     ]);
    // }

    #[Route('/{id}', name: 'app_like_show', methods: ['GET'])]
    public function show(Like $like): Response
    {
        return $this->render('like/show.html.twig', [
            'like' => $like,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_like_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LikeType::class, $like);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('like/edit.html.twig', [
            'like' => $like,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_like_delete', methods: ['POST'])]
    public function delete(Request $request, Like $like, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$like->getId(), $request->request->get('_token'))) {
            $entityManager->remove($like);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_like_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/commentaire/{id}/like/new', name: 'app_commentaire_like', methods: ['POST'])]
    public function commentaireLike(Request $request, Commentaire $commentaire, Publication $publication): Response
    {
        $user = $this->getUser();
    
        // Vérifiez si l'utilisateur est connecté
        if (!$user) {
            // Gérez le cas où l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_home');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
    
        // Vérifiez le jeton CSRF
        if ($this->isCsrfTokenValid('commentaireLike' . $commentaire->getId(), $request->request->get('_token'))) {
            // Vérifiez si l'utilisateur a déjà liké ce commentaire
            $existingLike = $entityManager->getRepository(Like::class)->findOneBy([
                'user' => $user,
                'commentaire' => $commentaire,
            ]);
        
            if ($existingLike) {
                // Si un like existe déjà, supprimez-le (toggle)
                $entityManager->remove($existingLike);
            } else {
                // Sinon, créez un nouveau like
                $like = new Like();
                $like->setUser($user);
                $like->setCommentaire($commentaire);
                $entityManager->persist($like);
            }
        
            $entityManager->flush();
        }

        // Récupérez l'ID de la publication associée au commentaire
        $publicationId = $commentaire->getPublication()->getId();
    
        return $this->redirectToRoute('app_publication_show', ['id' => $publicationId]);
    }
   
    #[Route('/publication/{id}/like', name: 'app_publication_like', methods: ['POST'])]
    public function publicationLike(Request $request, Publication $publication): Response
    {
        $user = $this->getUser();
    
        // Vérifiez si l'utilisateur est connecté
        if (!$user) {
            // Gérez le cas où l'utilisateur n'est pas connecté
            return $this->redirectToRoute('app_home');
        }
    
        $entityManager = $this->getDoctrine()->getManager();
    
        // Vérifiez le jeton CSRF
        if ($this->isCsrfTokenValid('publicationLike' . $publication->getId(), $request->request->get('_token'))) {
            // Vérifiez si l'utilisateur a déjà liké ce commentaire
            $existingLike = $entityManager->getRepository(Like::class)->findOneBy([
                'user' => $user,
                'publication' => $publication,
            ]);
            
            if ($existingLike) {
                // Si un like existe déjà, supprimez-le (toggle)
                $entityManager->remove($existingLike);
            } else {
                // Sinon, créez un nouveau like
                $like = new Like();
                $like->setUser($user);
                $like->setPublication($publication);
                $entityManager->persist($like);
            }
        
            $entityManager->flush();
        }
    
        return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId()]);
   
    }
}