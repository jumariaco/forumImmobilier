<?php

namespace App\Controller\Admin;

use App\Entity\Titre;
use App\Form\TitreType;
use App\Repository\TitreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin/titre')]
class TitreController extends AbstractController
{
    #[Route('/', name: 'app_admin_titre_index', methods: ['GET'])]
    public function index(TitreRepository $titreRepository): Response
    {
        return $this->render('admin/titre/index.html.twig', [
            'titres' => $titreRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_admin_titre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $titre = new Titre();
        $form = $this->createForm(TitreType::class, $titre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($titre);
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_titre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/titre/new.html.twig', [
            'titre' => $titre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_titre_show', methods: ['GET'])]
    public function show(Titre $titre): Response
    {
        return $this->render('admin/titre/show.html.twig', [
            'titre' => $titre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_admin_titre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Titre $titre, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TitreType::class, $titre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_admin_titre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/titre/edit.html.twig', [
            'titre' => $titre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_admin_titre_delete', methods: ['POST'])]
    public function delete(Request $request, Titre $titre, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$titre->getId(), $request->request->get('_token'))) {
            $entityManager->remove($titre);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin_titre_index', [], Response::HTTP_SEE_OTHER);
    }

 
}
