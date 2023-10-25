<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory as FakerFactory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture implements FixtureGroupInterface
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
        return ['prod','test'];
    }

    public function load(ObjectManager $manager): void
    {
        $this->manager = $manager;

        $this->loadAdmins();
    }

    public function loadAdmins () : void 
    {
        //données statiques
        $datas= [
            [
                'email'=>'admin@example.com',
                'pseudo'=>'testadmin',
                'password'=>'123',
                'roles'=>['ROLE_ADMIN'],
                'actif'=>true,
                'acceptationCgu'=>true,
                'newsletter'=>true
            ],
        ];

        foreach($datas as $data){
        $user = new User();
        $user->setEmail($data['email']);
        $user->setPseudo($data['pseudo']);
        $password = $this->hasher->hashPassword($user, $data['password']);
        $user->setPassword($password);
        $user->setRoles($data['roles']);
        $user->setActif($data['actif']);
        $user->setAcceptationCgu($data['acceptationCgu']);
        $user->setNewsletter($data['newsletter']);
         //ces 2 fonctions ci-dessous transforment php en sql pour injecter ds bdd
        //  persist = stocke dans la base de données
        // il est dans la boucle car génère un nouvel User à chaque nouvelle boucle en écransant l'ancien
        $this->manager->persist($user);
        }
        // flush génère du SQL pour le rentrer dans la base de données
        $this->manager->flush();
    }
}