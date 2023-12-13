<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Domaine;
use App\Entity\Publication;
use App\Form\AdminProfileType;
use App\Form\MembreProfileType;
use App\Form\PartenaireProfileType;
use App\Form\UserPasswordType;
use App\Repository\UserRepository;
use App\Repository\PublicationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile')]
class ProfileController extends AbstractController
{
    #[Route('/', name: 'app_profile_index', methods: ['GET'])]
    public function index(UserRepository $userRepository): Response
    {

        return $this->render('profile/index.html.twig', [
            // 'users' => $userRepository->findAll(),
            'usersActifs' => $userRepository->FindByUserActif(),
        ]);
    }

    #[Route('/abonnements', name: 'app_profile_abonnements', methods: ['GET'])]
    public function abonnements(UserRepository $userRepository): Response
    {
        // Récupérez l'utilisateur connecté
        $currentUser = $this->getUser();

        // Appelez la fonction avec l'utilisateur connecté comme argument
        $usersSuivis = $userRepository->findByUsersSuivis($currentUser);

        return $this->render('profile/abonnements.html.twig', [
            'usersSuivis' => $usersSuivis,
        ]);
    }

    // #[Route('/new-membre', name: 'app_profile_new_membre', methods: ['GET', 'POST'])]
    // public function newMembre(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(MembreProfileType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user
    //             ->setRoles(['ROLE_MEMBRE'])
    //         ;
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('profile/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // #[Route('/new-partenaire', name: 'app_profile_new_partenaire', methods: ['GET', 'POST'])]
    // public function newPartenaire(Request $request, EntityManagerInterface $entityManager): Response
    // {
    //     $user = new User();
    //     $form = $this->createForm(PartenaireProfileType::class, $user);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $user
    //             ->setRoles(['ROLE_PARTENAIRE'])
    //         ;
    //         $entityManager->persist($user);
    //         $entityManager->flush();

    //         return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    //     }

    //     return $this->renderForm('profile/new.html.twig', [
    //         'user' => $user,
    //         'form' => $form,
    //     ]);
    // }

    // route pour créer un admin 
    // totalement différente de celles pour créer un membre(registration) ou partenaire (registration-partenaire) dans SecurityController
    // question de sécurité
    #[Route('/new-admin', name: 'app_profile_new_admin', methods: ['GET', 'POST'])]
    public function newAdmin(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(AdminProfileType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user
                ->setRoles(['ROLE_ADMIN'])
            ;
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_profile_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}/follow', name: 'app_user_follow', methods: ['POST'])]
    public function follow(Request $request, User $user): Response
    {
        $currentUser = $this->getUser();

        // Vérifiez si l'utilisateur est connecté
        if (!$currentUser) {
            return $this->redirectToRoute('app_home');
        }

        // Vérifiez le jeton CSRF
        if ($this->isCsrfTokenValid('follow' . $user->getId(), $request->request->get('_token'))) {
            // Vérifiez si l'utilisateur suit déjà le profil
            if ($currentUser->getAbonnes()->contains($user)) {
                $currentUser->removeAbonne($user);
            } else {
                $currentUser->addAbonne($user);
            }

            $this->getDoctrine()->getManager()->flush();
        }

        return $this->redirectToRoute('app_profile_show', ['id' => $user->getId()]);
    }


    #[Route('/{id}/publication-sans-reponse', name: 'app_profile_publication_sans_reponse', methods: ['GET', 'POST'])]
    public function publicationSansReponse(User $user, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('profile/publication_sans_reponse.html.twig', [
            'publicationSansReponse' => $publicationRepository->PublicationPublieeSansReponse(),
            'user' => $user,
            
        ]);
    }

    #[Route('/{id}/publication-close', name: 'app_profile_publication_close', methods: ['GET', 'POST'])]
    public function publicationClose(User $user, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('profile/publication_close.html.twig', [
            'publicationClose' => $publicationRepository->PublicationClose(),
            'user' => $user,
            
        ]);
    }

    #[Route('/{id}/brouillon', name: 'app_profile_brouillon', methods: ['GET', 'POST'])]
    public function brouillon(User $user, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('profile/brouillon.html.twig', [
            'brouillon' => $publicationRepository->Brouillon(),
            'user' => $user,
            
        ]);
    }

    #[Route('/{id}/commentaire', name: 'app_profile_commentaire', methods: ['GET', 'POST'])]
    public function commentaire(User $user, PublicationRepository $publicationRepository, EntityManagerInterface $entityManager): Response
    {
       
        return $this->render('profile/commentaire.html.twig', [
            'user' => $user,
            
        ]);
    }


  
    #[Route('/{id}/edit', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if(in_array('ROLE_PARTENAIRE', $user->getRoles())){
            $form=$this->createForm(PartenaireProfileType::class, $user);
        }if(in_array('ROLE_MEMBRE', $user->getRoles())){
            $form=$this->createForm(MembreProfileType::class, $user);
        }
        // $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/password', name: 'app_profile_password', methods: ['GET', 'POST'])]
    public function password(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form= $this->createForm(UserPasswordType::class, $user);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
    #[Route('/{id}', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
