<?php

namespace App\Repository;

use App\Entity\ConsumptionApproval;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsumptionApproval|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsumptionApproval|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsumptionApproval[]    findAll()
 * @method ConsumptionApproval[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumptionApprovalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumptionApproval::class);
    }

    // /**
    //  * @return ConsumptionApproval[] Returns an array of ConsumptionApproval objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ConsumptionApproval
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
