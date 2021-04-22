<?php

namespace App\Repository;

use App\Entity\DatePurchased;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DatePurchased|null find($id, $lockMode = null, $lockVersion = null)
 * @method DatePurchased|null findOneBy(array $criteria, array $orderBy = null)
 * @method DatePurchased[]    findAll()
 * @method DatePurchased[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DatePurchasedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DatePurchased::class);
    }

    // /**
    //  * @return DatePurchased[] Returns an array of DatePurchased objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DatePurchased
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
