<?php

namespace App\Repository;

use App\Entity\StockList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockList|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockList|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockList[]    findAll()
 * @method StockList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockList::class);
    }

    // /**
    //  * @return StockList[] Returns an array of StockList objects
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
    public function findOneBySomeField($value): ?StockList
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
