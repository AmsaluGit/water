<?php

namespace App\Repository;

use App\Entity\ConsumptionRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsumptionRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsumptionRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsumptionRequest[]    findAll()
 * @method ConsumptionRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumptionRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumptionRequest::class);
    }

    // /**
    //  * @return ConsumptionRequest[] Returns an array of ConsumptionRequest objects
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
    public function findOneBySomeField($value): ?ConsumptionRequest
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
