<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Domaine;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }



    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * @return User[] Returns an array of User objects
     */

   
    public function RechercheUtilisateur($keyword): array
    {
        return $this->createQueryBuilder('u')
        ->where('u.Pseudo LIKE :keyword')
        ->setParameter('keyword', '%' . $keyword . '%')
        ->andWhere('u.roles NOT LIKE :roleAdmin')
        ->setParameter('roleAdmin', '%ROLE_ADMIN%')
        ->andWhere('u.actif = :actif')
        ->setParameter('actif', true)
        ->addOrderBy('u.roles', 'DESC')
        ->addOrderBy('u.Pseudo', 'ASC')
        ->getQuery()
        ->getResult();
        
    }

    /**
     * @return User[] Returns an array of active User objects
     */
    public function FindByUserActif()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles NOT LIKE :roleAdmin')
            ->setParameter('roleAdmin', '%ROLE_ADMIN%')
            ->andWhere('u.actif = :actif')
            ->setParameter('actif', true)
            ->addOrderBy('u.roles', 'DESC')
            ->addOrderBy('u.Pseudo', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return User[] Returns an array of active User objects
     */
    public function FindByPartenaireNonActif()
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.roles LIKE :rolePartenaire')
            ->setParameter('rolePartenaire', '%ROLE_PARTENAIRE%')
            ->andWhere('u.actif IS NULL')
            ->addOrderBy('u.createdAt', 'ASC')
            ->addOrderBy('u.Pseudo', 'ASC')
            ->getQuery()
            ->getResult();
    }
    
//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
