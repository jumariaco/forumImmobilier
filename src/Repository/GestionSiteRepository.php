<?php

namespace App\Repository;

use App\Entity\GestionSite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<GestionSite>
 *
 * @method GestionSite|null find($id, $lockMode = null, $lockVersion = null)
 * @method GestionSite|null findOneBy(array $criteria, array $orderBy = null)
 * @method GestionSite[]    findAll()
 * @method GestionSite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GestionSiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GestionSite::class);
    }

//    /**
//     * @return GestionSite[] Returns an array of GestionSite objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?GestionSite
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
