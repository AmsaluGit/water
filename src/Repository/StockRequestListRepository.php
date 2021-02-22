<?php

namespace App\Repository;

use App\Entity\StockRequestList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockRequestList|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockRequestList|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockRequestList[]    findAll()
 * @method StockRequestList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRequestListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockRequestList::class);
    }

    // /**
    //  * @return StockRequestList[] Returns an array of StockRequestList objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StockRequestList
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
