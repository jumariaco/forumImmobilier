<?php

namespace App\Repository;

use App\Entity\Domaine;
use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @extends ServiceEntityRepository<Publication>
 *
 * @method Publication|null find($id, $lockMode = null, $lockVersion = null)
 * @method Publication|null findOneBy(array $criteria, array $orderBy = null)
 * @method Publication[]    findAll()
 * @method Publication[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PublicationRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Publication::class);
        $this->security = $security;
    }

    
    /**
     * Get published publications and articles thanks to Search Data Value
     * @return Publication[]
     */
    public function RecherchePublication($keyword)
    {
    return $this->createQueryBuilder('p')
        ->andWhere('p.statut = :statut')
        ->setParameter('statut', true)
        ->addOrderBy('p.createdAt', 'DESC')
        ->leftJoin('p.domaines', 'd')
        ->leftJoin('p.user', 'u')  //user singulier car relation Many To One
        ->andWhere('p.titre LIKE :keyword OR p.contenu LIKE :keyword OR d.nom LIKE :keyword OR u.Pseudo LIKE :keyword')  // Attention Pseudo avec majuscule
        ->setParameter('keyword', '%' . $keyword . '%')
        ->getQuery()
        ->getResult();
    }

    /**
     * Get published publications written by a partner or member
     * @return Publication[]
     */
    public function PublicationPubliee()
    {
    return $this->createQueryBuilder('p')
    ->innerJoin('p.user', 'u')  //user singulier car relation Many To One
    ->andWhere('u.roles NOT LIKE :roleAdmin')
    ->setParameter('roleAdmin', '%ROLE_ADMIN%')
    ->andWhere('p.statut = :statut')
    ->setParameter('statut', true)
    ->addOrderBy('p.createdAt', 'DESC')
    ->getQuery()
    ->getResult();
    }

 
    /**
     * Get brouillon publication written by a member
     * @return Publication[]
     */
    public function PublicationBrouillonMembre()
    {
    return $this->createQueryBuilder('p')
        ->innerJoin('p.user', 'u')  //user singulier car relation Many To One
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%ROLE_MEMBRE%')
        ->andWhere('p.statut = :statut')
        ->setParameter('statut', false)
        ->addOrderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }
  
    /**
     * Get published articles written by an admin
     * @return Publication[]
     */
    public function ArticlePublie()
    {
    return $this->createQueryBuilder('p')
        ->innerJoin('p.user', 'u')  //user singulier car relation Many To One
        ->andWhere('p.statut = :statut')
        ->setParameter('statut', true)
        ->andWhere('u.roles LIKE :role')
        ->setParameter('role', '%ROLE_ADMIN%')
        ->addOrderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }
    /**
     * Get brouillon publications and articles
     * @return Publication[]
     */
    public function Brouillon()
    {
        $user = $this->security->getUser();
    return $this->createQueryBuilder('p')
        ->innerJoin('p.user', 'u')  //user singulier car relation Many To One
        ->andWhere('u= :user')
        ->setParameter('user', $user)
        ->andWhere('p.statut = :statut')
        ->setParameter('statut', false)
        ->addOrderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }

    /**
     * Get published publications and articles thanks to search by Domaine
     * @return Publication[] 
     */
    public function findByDomaine(Domaine $domaine): array
  {
    return $this->createQueryBuilder('p')
        ->innerJoin('p.domaines', 'd')  
        ->andWhere('p.statut = :statut')
        ->setParameter('statut', true)
        ->andWhere('d = :domaine')  // Ajoutez une condition pour filtrer par le domaine spécifié
        ->setParameter('domaine', $domaine)
        ->addOrderBy('p.createdAt', 'DESC')
        ->getQuery()
        ->getResult();

    }

   

//    /**
//     * @return Publication[] Returns an array of Publication objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Publication
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
