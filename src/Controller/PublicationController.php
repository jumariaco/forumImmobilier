<?php

namespace App\Controller;

use App\Entity\Publication;
use App\Entity\Commentaire;
use App\Form\PublicationType;
use App\Form\CommentaireType;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/publication')]
class PublicationController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/', name: 'app_publication_index', methods: ['GET'])]
    public function index(PublicationRepository $publicationRepository): Response
    {
        return $this->render('publication/index.html.twig', [
            'publications' => $publicationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_publication_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $publication = new Publication();
        $form = $this->createForm(PublicationType::class, $publication);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($publication);
            $entityManager->flush();

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/new.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    // #[Route('/{id}', name: 'app_publication_show', methods: ['GET', 'POST'])]
    // public function show(Publication $publication, Request $request): Response
    // {

    //     // partie Commentaires
    //     // On crée l'objet "Commentaire" vierge
    //     $commentaire = new Commentaire();

    //     // on génère le formulaire
    //     $commentaireForm = $this->createForm(CommentaireType::class, $commentaire);
    //     $commentaireForm->handleRequest($request);

    //     // Traitement du formulaire
    //     if ($commentaireForm->isSubmitted() && $commentaireForm->isValid()) {
    //         $commentaire->setCreatedAt(new \DateTime());
    //         $commentaire->setPublication($publication);

    //         // On récupère le contenu du champ parentid
    //         $parentid = $commentaireForm->get('parentid')->getData();

    //         // On ajoute l'utilisateur connecté à l'entité Commentaire
    //         $commentaire->setUser($this->getUser());

    //         // On va chercher le commentaire correspondant
    //         $entityManager = $this->getDoctrine()->getManager();

    //         if($parentid != null) {
    //             $parent = $entityManager->getRepository(Commentaire::class)->find($parentid);
    //             // on définit le parent
    //             $commentaire->setParent($parent);
    //         } else {
    //             $commentaire->setParent(null);
    //         }
            
    //         $this->entityManager->persist($commentaire);
    //         $this->entityManager->flush();

    //         $this->addFlash('success', 'Commentaire ajouté');
    //         return $this->redirectToRoute('app_publication_show', ['id' => $publication->getId()]);
    //         // return $this->redirectToRoute('publication', ['slug' => $publication->getSlug()]);
    //     }


    //     return $this->render('publication/show.html.twig', [
    //         'publication' => $publication,
    //         'commentaireForm' => $commentaireForm->createView(),
    //     ]);
    // }

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

            return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('publication/edit.html.twig', [
            'publication' => $publication,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_publication_delete', methods: ['POST'])]
    public function delete(Request $request, Publication $publication, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$publication->getId(), $request->request->get('_token'))) {
            $entityManager->remove($publication);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_publication_index', [], Response::HTTP_SEE_OTHER);
    }
}
