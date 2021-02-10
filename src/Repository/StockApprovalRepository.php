<?php

namespace App\Repository;

use App\Entity\StockApproval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method StockApproval|null find($id, $lockMode = null, $lockVersion = null)
 * @method StockApproval|null findOneBy(array $criteria, array $orderBy = null)
 * @method StockApproval[]    findAll()
 * @method StockApproval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockApprovalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StockApproval::class);
    }

    // /**
    //  * @return StockApproval[] Returns an array of StockApproval objects
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
    public function findOneBySomeField($value): ?StockApproval
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
