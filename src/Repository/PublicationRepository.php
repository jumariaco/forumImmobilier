<?php

namespace App\Repository;

use App\Entity\Domaine;
use App\Entity\Publication;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

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
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Publication::class);
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
