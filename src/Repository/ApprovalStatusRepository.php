<?php

namespace App\Repository;

use App\Entity\ApprovalStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApprovalStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApprovalStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApprovalStatus[]    findAll()
 * @method ApprovalStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApprovalStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApprovalStatus::class);
    }

    // /**
    //  * @return ApprovalStatus[] Returns an array of ApprovalStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApprovalStatus
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
