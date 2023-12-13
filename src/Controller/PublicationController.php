<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Commentaire;
use App\Form\PublicationType;
use App\Form\CommentaireType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;


#[Route('/publication')]
class PublicationController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    // #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    // public function index(PublicationRepository $publicationRepository): Response
    // {
    //     return $this->render('publication/index.html.twig', [
    //         'publications' => $publicationRepository->findAll(),
            
    //     ]);
    // }
    
    #[Route('/active', name: 'app_publication_active', methods: ['GET', 'POST'])]
    public function publicationActive(PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('publication/active.html.twig', [
            // 'publications' => $publications,
            'publicationActive' => $publicationRepository->PublicationPublieeActive(),
            
        ]);
    }


    #[Route('/sans-reponse', name: 'app_publication_sans_reponse', methods: ['GET', 'POST'])]
    public function publicationSansReponse(PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('publication/sans_reponse.html.twig', [
            'publicationSansReponse' => $publicationRepository->PublicationPublieeSansReponse(),
            
        ]);
    }


    #[Route('/close', name: 'app_publication_close', methods: ['GET', 'POST'])]
    public function publicationClose(PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('publication/close.html.twig', [
            'publicationClose' => $publicationRepository->PublicationClose(),
            
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, AuthorizationCheckerInterface $authorizationChecker): Response
    {
        $publication = new Publication();

        if($this->getUser()){
            $publication->setUser($this->getUser());
        }

        
 
        $form = $this->createForm(PublicationType::class, $publication, [
            'is_membre' => $authorizationChecker->isGranted('ROLE_MEMBRE'),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Définir le statut en fonction du rôle de l'utilisateur
            if ($authorizationChecker->isGranted('ROLE_PARTENAIRE') || $authorizationChecker->isGranted('ROLE_ADMIN')) {
                $publication->setStatut(true);
            } else { //rôle membre
                $publication->setStatut(false);
            }
            $entityManager->persist($publication);
            $entityManager->flush();

            // réussir à avoir un addFlash pour afficher que la publication est bien validée (ou en attente de validtaion)

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }


    // PUBLICATION AVEC COMMENTAIRES IMBRIQUES
     #[Route('/{id}', name: 'app_publication_show', methods: ['GET', 'POST'])]
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

   

    #[Route('/{id}/edit', name: 'app_publication_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }


   




    // #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    // public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
    //         $entityManager->remove($publication);
    //         $entityManager->flush();
    //     }

    //     return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    // }
}
