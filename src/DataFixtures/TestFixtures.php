<?php

namespace App\DataFixtures;

use App\Entity\Commentaire;
use App\Entity\Domaine;
use App\Entity\Membre;
use App\Entity\Partenaire;
use App\Entity\Publication;
use App\Entity\Titre;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TestFixtures extends Fixture implements FixtureGroupInterface
{
    private $faker;
    private $hasher;
    private $manager;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->faker = FakerFactory::create('fr_FR');
        $this->hasher = $hasher;
    }

    public static function getGroups(): array
    {
        return ['test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadDomaines();
        $this->loadTitres();
        $this->loadUsers();
        $this->loadPartenaires();
        $this->loadPublications();
        $this->loadCommentaires();
    }

    public function loadDomaines(): void 
    {
        //données statiques
        $datas=[
            ['domaine'=>'transaction'],
            ['domaine'=>'location/ gestion locative'],
            ['domaine'=>'construction'],
            ['domaine'=>'gestion de copropriété'],
            ['domaine'=>'droit d\'urbanisme'],
            ['domaine'=>'droit de la famille'],
            ['domaine'=>'investissement locatif'],  
            ['domaine'=>'viager'],      
            
        ];
        foreach($datas as $data) {
            $domaine=new Domaine();
            $domaine->setNom($data['domaine']);
                        
            $this->manager->persist($domaine);
        }
        $this->manager->flush();
    }

    public function loadTitres(): void 
    {
        //données statiques
        $datas=[
            ['titre'=>'Monsieur'],
            ['titre'=>'Madame'],
            ['titre'=>'Mx'],
            ['titre'=>'Maître'],
            ['titre'=>'Docteur'],
            ['titre'=>'Professeur'],           
        ];
        foreach($datas as $data) {
            $titre=new Titre();
            $titre->setNom($data['titre']);
            
            $this->manager->persist($titre);
        }
        $this->manager->flush();
    }
    public function loadUsers(): void
    {
        //relation ManytoMany : avec domaines
        $domaineRepository =$this->manager->getRepository(Domaine::class);
        $domaines=$domaineRepository->findAll();
        $domaine1=$domaineRepository->find(1);
        $domaine2=$domaineRepository->find(2);
        //relation ManytoOne : avec titre
        $titreRepository =$this->manager->getRepository(Titre::class);
        $titres=$titreRepository->findAll();
        $titre1=$titreRepository->find(1);
        $titre2=$titreRepository->find(2);
        //données statiques
        $datas = [
            [
                'email'=>'membre@example.com',
                'password'=>'123',
                'pseudo'=>'membretest',
                'roles'=>['ROLE_MEMBRE'],
                'actif'=>true,
                'acceptationCgu'=>true,
                'newsletter'=>true,
                'nom'=>'membre',
                'prenom'=>'test',
                'metier'=>'avocat',
                'domaines'=>[$domaine1,$domaine2],
                'titre'=>$titre1,
            ],
            [
                'email'=>'membre1@example.com',
                'password'=>'123',
                'pseudo'=>'membre1test',
                'roles'=>['ROLE_MEMBRE'],
                'actif'=>true,
                'acceptationCgu'=>true,
                'newsletter'=>false,
                'nom'=>'membre1',
                'prenom'=>'test',
                'metier'=>'professeur',
                'domaines'=>[$domaine2],
                'titre'=>$titre2,
            ],
        ];

        foreach ($datas as $data) {
            $membre=new Membre();
            $membre->setNom($data['nom']);
            $membre->setPrenom($data['prenom']);
            $membre->setMetier($data['metier']);
            foreach ($data['domaines'] as $domaine) {
                $membre->addDomaine($domaine);
            }
            $membre->setTitre($data['titre']);

            $this->manager->persist($membre);

            $user=new User();
            $user->setEmail($data['email']);
            $password=$this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setPseudo($data['pseudo']);
            $user->setRoles($data['roles']);
            $user->setActif($data['actif']);
            $user->setAcceptationCgu($data['acceptationCgu']);
            $user->setNewsletter($data['newsletter']);
            $user->setMembre($membre);


            $this->manager->persist($user);
        }
        $this->manager->flush();

        //données dynamiques
        for ($i=0; $i < 20; $i++){
            $membre=new Membre();
            $membre->setNom($this->faker->lastName());
            $membre->setPrenom($this->faker->firstName());
            $membre->setMetier($this->faker->jobTitle());
            $domainesCount = random_int(1,5);
            $shortList=$this->faker->randomElements($domaines, $domainesCount);
            foreach ($shortList as $domaine) {
                $membre->addDomaine($domaine);
            }
            $membre->setTitre($this->faker->randomElement($titres));
            $createdAt = $this->faker->dateTimeBetween ('-2 year', '-1 month');
            $membre->setCreatedAt($createdAt);

            $this->manager->persist($membre);

            $user=new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password=$this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setPseudo($this->faker->unique()->userName());
            $user->setRoles(['ROLE_MEMBRE']);
            $user->setActif($this->faker->boolean(95));
            $user->setAcceptationCgu($this->faker->boolean(100));
            $user->setNewsletter($this->faker->boolean(70));
            $user->setMembre($membre);

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }
    public function loadPartenaires(): void
    {
        //relation ManytoMany : avec domaines
        $domaineRepository =$this->manager->getRepository(Domaine::class);
        $domaines=$domaineRepository->findAll();
        $domaine1=$domaineRepository->find(1);
        $domaine2=$domaineRepository->find(2);
        //relation ManytoOne : avec titre
        $titreRepository =$this->manager->getRepository(Titre::class);
        $titres=$titreRepository->findAll();
        $titre1=$titreRepository->find(1);
        $titre2=$titreRepository->find(2);
        //données statiques
        $datas = [
            [
                'email'=>'partenaire@example.com',
                'password'=>'123',
                'pseudo'=>'partenairetest',
                'roles'=>['ROLE_PARTENAIRE'],
                'actif'=>true,
                'acceptationCgu'=>true,
                'newsletter'=>true,
                'nom'=>'partenaire',
                'prenom'=>'test',
                'societe'=>'X',
                'siret'=> 123456789,
                'metier'=>'avocat',
                'telephone'=>'123456789',
                'emailContact'=>'contact@example.com',
                'departement'=>'75',
                'domaines'=>[$domaine1,$domaine2],
                'titre'=>$titre1,
            ],
            [
                'email'=>'partenaire1@example.com',
                'password'=>'123',
                'pseudo'=>'partenaire1test',
                'roles'=>['ROLE_PARTENAIRE'],
                'actif'=>true,
                'acceptationCgu'=>true,
                'newsletter'=>false,
                'nom'=>'partenaire1',
                'prenom'=>'test',
                'societe'=>'Y',
                'siret'=> 123456789,
                'metier'=>'commercial',
                'telephone'=>'123456789',
                'emailContact'=>'contacty@example.com',
                'departement'=>'59',
                'domaines'=>[$domaine1],
                'titre'=>$titre1,
            ],
        ];

        foreach ($datas as $data) {
            $partenaire=new Partenaire();
            $partenaire->setNom($data['nom']);
            $partenaire->setPrenom($data['prenom']);
            $partenaire->setSociete($data['societe']);
            $partenaire->setSiret($data['siret']);
            $partenaire->setMetier($data['metier']);
            $partenaire->setTelephone($data['telephone']);
            $partenaire->setEmailContact($data['emailContact']);
            $partenaire->setDepartement($data['departement']);
            foreach ($data['domaines'] as $domaine) {
                $partenaire->addDomaine($domaine);
            }
            $partenaire->setTitre($data['titre']);

            $this->manager->persist($partenaire);

            $user=new User();
            $user->setEmail($data['email']);
            $password=$this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setPseudo($data['pseudo']);
            $user->setRoles($data['roles']);
            $user->setActif($data['actif']);
            $user->setAcceptationCgu($data['acceptationCgu']);
            $user->setNewsletter($data['newsletter']);
            $user->setPartenaire($partenaire);

            $this->manager->persist($user);
        }
        $this->manager->flush();

    //     //données dynamiques
        for ($i=0; $i < 10; $i++){
            $partenaire=new Partenaire();
            $partenaire->setNom($this->faker->lastName());
            $partenaire->setPrenom($this->faker->firstName());
            $partenaire->setSociete($this->faker->company());
            $partenaire->setSiret($this->faker->randomNumber(9));
            $partenaire->setMetier($this->faker->jobTitle());
            $partenaire->setTelephone($this->faker->phoneNumber());
            $partenaire->setEmailContact($this->faker->email());
            $partenaire->setDepartement($this->faker->numberBetween(1,97));
            $domainesCount = random_int(1,5);
            $shortList=$this->faker->randomElements($domaines, $domainesCount);
            foreach ($shortList as $domaine) {
                $partenaire->addDomaine($domaine);
            }
            $partenaire->setTitre($this->faker->randomElement($titres));
            $createdAt = $this->faker->dateTimeBetween ('-2 year', '-2 month');
            $partenaire->setCreatedAt($createdAt);

            $this->manager->persist($partenaire);

            $user=new User();
            $user->setEmail($this->faker->unique()->safeEmail());
            $password=$this->hasher->hashPassword($user, '123');
            $user->setPassword($password);
            $user->setPseudo($this->faker->unique()->userName());
            $user->setRoles(['ROLE_MEMBRE']);
            $user->setActif($this->faker->boolean(95));
            $user->setAcceptationCgu($this->faker->boolean(100));
            $user->setNewsletter($this->faker->boolean(70));
            $user->setPartenaire($partenaire);

            $this->manager->persist($user);
        }
        $this->manager->flush();
    }

    public function loadPublications(): void
    {
        //relation ManytoOne : avec User
        $userRepository =$this->manager->getRepository(User::class);
        $users=$userRepository->findAll();
        $user2=$userRepository->find(2);
        $user3=$userRepository->find(3);
        //relation ManytoMany : table de jointure publication_domaine
        $domaineRepository =$this->manager->getRepository(Domaine::class);
        $domaines=$domaineRepository->findAll();
        $domaine1=$domaineRepository->find(1);
        $domaine2=$domaineRepository->find(2);
        $domaine3=$domaineRepository->find(3);
        //données statiques
        $datas = [
            [
                'titre'=>'Lorem Ipsum',
                'contenu'=>' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim.',
                'statut'=>0,
                'user'=>$user2,
                'domaines'=>[$domaine1, $domaine2],
            ],
            [
                'titre'=>'Lolorem Ipsumsum',
                'contenu'=>' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim.',
                'statut'=>1,
                'user'=>$user3,
                'domaines'=>[$domaine3, $domaine2],
            ],
        ];

        foreach ($datas as $data) {
            $publication=new Publication();
            $publication->setTitre($data['titre']);
            $publication->setContenu($data['contenu']);
            $publication->setStatut($data['statut']);
            $publication->setUser($data['user']);
            foreach ($data['domaines'] as $domaine) {
                $publication->addDomaine($domaine);
            }
            $this->manager->persist($publication);
        }
        $this->manager->flush();

        //données dynamiques
        for ($i=0; $i < 15; $i++){
            $publication=new Publication();
            $words=random_int(2,8);
            $publication->setTitre($this->faker->sentence($words));
            $publication->setContenu($this->faker->text());
            $publication->setStatut(1);
            $domainesCount = random_int(1,5);
            $shortList=$this->faker->randomElements($domaines, $domainesCount);
            foreach ($shortList as $domaine) {
                $publication->addDomaine($domaine);
            }
            $publication->setUser($userRepository->find($this->faker->numberBetween(1,20)));
            $createdAt = $this->faker->dateTimeBetween ('-2 month', '-1 month');
            $publication->setCreatedAt($createdAt);

            $this->manager->persist($publication);
        }
        $this->manager->flush();
    }

    public function loadCommentaires(): void
    {
        //relation ManytoOne : avec Publication
        $publicationRepository =$this->manager->getRepository(Publication::class);
        $publications=$publicationRepository->findAll();
        $publication1=$publicationRepository->find(1);
       //relation ManytoOne : avec User
       $userRepository =$this->manager->getRepository(User::class);
       $users=$userRepository->findAll();
       $user3=$userRepository->find(3);
       $user4=$userRepository->find(4);
        //données statiques
        $datas = [
            [
                'contenu'=>' iscing elit. Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim.',
                'choixRetenu'=>0,
                'publication'=>$publication1,
                'user'=>$user3,
            ],
            [
                'contenu'=>' Lorems Sed non risus. Suspendisse lectus tortor, dignissim sit amet, adipiscing nec, ultricies sed, dolor. Cras elementum ultrices diam. Maecenas ligula massa, varius a, semper congue, euismod non, mi. Proin porttitor, orci nec nonummy molestie, enim est eleifend mi, non fermentum diam nisl sit amet erat. Duis semper. Duis arcu massa, scelerisque vitae, consequat in, pretium a, enim.',
                'choixRetenu'=>1,
                'publication'=>$publication1,
                'user'=>$user4,
            ],
        ];

        foreach ($datas as $data) {
            $commentaire=new Commentaire();
            $commentaire->setContenu($data['contenu']);
            $commentaire->setChoixRetenu($data['choixRetenu']);
            $commentaire->setPublication($data['publication']);
            $commentaire->setUser($data['user']);
            $this->manager->persist($commentaire);
        }
        $this->manager->flush();

        //données dynamiques
        for ($i=0; $i < 30; $i++){
            $commentaire=new Commentaire();
            $commentaire->setContenu($this->faker->text());
            $commentaire->setChoixRetenu($this->faker->boolean(10));
            $commentaire->setPublication($publicationRepository->find($this->faker->numberBetween(1,15)));
            $commentaire->setUser($userRepository->find($this->faker->numberBetween(2,20)));
            $createdAt = $this->faker->dateTimeBetween ('-1 month', '-1 day');
            $commentaire->setCreatedAt($createdAt);

            $this->manager->persist($commentaire);
        }
        $this->manager->flush();
    }
}