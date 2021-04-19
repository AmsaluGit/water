<?php

namespace App\Repository;

use App\Entity\ProductionReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductionReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionReport[]    findAll()
 * @method ProductionReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionReport::class);
    }

    // /**
    //  * @return ProductionReport[] Returns an array of ProductionReport objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductionReport
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
