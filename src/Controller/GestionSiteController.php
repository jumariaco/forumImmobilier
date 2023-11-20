<?php

namespace App\Controller;

use App\Entity\GestionSite;
use App\Form\GestionSiteType;
use App\Repository\GestionSiteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/')]
class GestionSiteController extends AbstractController
{
    
    // #[Route('/gestion/site', name: 'app_gestion_site')]
    // public function index(): Response
    // {
    //     return $this->render('gestion_site/index.html.twig', [
    //         'controller_name' => 'GestionSiteController',
    //     ]);
    // }

    #[Route('/gestion-site/new', name: 'app_gestion_site_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $gestionSite = new GestionSite();
        $form = $this->createForm(GestionSiteType::class, $gestionSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($gestionSite);
            $entityManager->flush();

            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/gestionSite/new.html.twig', [
            'gestionSite' => $gestionSite,
            'form' => $form,
        ]);
    }
    #[Route('/mentions-legales', name: 'app_mentions_legales', methods: ['GET', 'POST'])]
    public function mentions(GestionSiteRepository $gestionSiteRepository): Response
    {
    
        return $this->render('admin/gestionSite/mentions.html.twig', [
            'mentions' => $gestionSiteRepository->findAll(),
        ]);
    }

    #[Route('/gestion-site/{id}/edit', name: 'app_mentions_legales_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GestionSite $gestionSite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GestionSiteType::class, $gestionSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_mentions_legales', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/gestionSite/mentions_edit.html.twig', [
            'gestionSite' => $gestionSite,
            'form' => $form,
        ]);
    }

    #[Route('/a-propos', name: 'app_a_propos', methods: ['GET', 'POST'])]
    public function apropos(GestionSiteRepository $gestionSiteRepository): Response
    {
    
        return $this->render('admin/gestionSite/apropos.html.twig',[
            'mentions' => $gestionSiteRepository->findAll(),
        ]);
    }

    #[Route('/gestion-site/{id}/edit', name: 'app_a_propos_edit', methods: ['GET', 'POST'])]
    public function editApropos(Request $request, GestionSite $gestionSite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GestionSiteType::class, $gestionSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_a_propos', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/gestionSite/apropos_edit.html.twig', [
            'gestionSite' => $gestionSite,
            'form' => $form,
        ]);
    }

    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function contact(GestionSiteRepository $gestionSiteRepository): Response
    {
    
        return $this->render('admin/gestionSite/contact.html.twig',[
            'mentions' => $gestionSiteRepository->findAll(),
        ]);
    }

    #[Route('/gestion-site/{id}/edit', name: 'app_contact_edit', methods: ['GET', 'POST'])]
    public function editContact(Request $request, GestionSite $gestionSite, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GestionSiteType::class, $gestionSite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_contact', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/gestionSite/contact_edit.html.twig', [
            'gestionSite' => $gestionSite,
            'form' => $form,
        ]);
    }
}
