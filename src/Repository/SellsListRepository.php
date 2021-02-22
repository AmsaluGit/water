<?php

namespace App\Repository;

use App\Entity\SellsList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SellsList|null find($id, $lockMode = null, $lockVersion = null)
 * @method SellsList|null findOneBy(array $criteria, array $orderBy = null)
 * @method SellsList[]    findAll()
 * @method SellsList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SellsListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SellsList::class);
    }

    // /**
    //  * @return SellsList[] Returns an array of SellsList objects
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
    public function findOneBySomeField($value): ?SellsList
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
