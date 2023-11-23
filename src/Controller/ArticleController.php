<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Commentaire;
use App\Form\ArticleType;
use App\Form\CommentaireType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/article')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository): Response
    {
        // $articlePublie = $publicationRepository->ArticlePublie();
        return $this->render('article/index.html.twig', [
            'articlePublie' => $publicationRepository->ArticlePublie(),
        ]);
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(ArticleType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('article/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET', 'POST'])]
    public function show(Publication $publication, Request $request, EntityManagerInterface $em): Response
    {
        
        $commentaire = new Commentaire();
        $commentaire->setPublication($publication);

        if($this->getUser()){
            $commentaire->setUser($this->getUser());
        }

        $form = $this->createForm(CommentaireType::class, $commentaire);
        
        $form->handleRequest($request);      
        
        
        if ($form->isSubmitted() && $form->isValid()) {
            $parentCommentId = $request->request->get('commentaire')['parent']; // récupère l'id du parent
            $enfantCommentId = $request->request->get('commentaire')['enfant']; // récupère l'id de l'enfant(parent) pour le petit-enfant
        if ($parentCommentId) {
            $parentComment = $em->getRepository(Commentaire::class)->find($parentCommentId);
            $commentaire->setParent($parentComment);
            }
        if ($enfantCommentId) {
            $enfantComment = $em->getRepository(Commentaire::class)->find($enfantCommentId);
            $commentaire->setEnfant($enfantComment);
        }

            $em->persist($commentaire);
            $em->flush();

            $this->addFlash('success', 'Votre commentaire a bien été ajouté');
           
            return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId()]);
        }
        


        return $this->render('publication/show.html.twig', [
            'publication' => $publication,
            'form' => $form->createView(),
        ]);
        
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ArticleType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }
}
