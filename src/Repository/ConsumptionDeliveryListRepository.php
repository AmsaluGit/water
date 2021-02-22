<?php

namespace App\Repository;

use App\Entity\ConsumptionDeliveryList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsumptionDeliveryList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsumptionDeliveryList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsumptionDeliveryList[]    findAll()
 * @method ConsumptionDeliveryList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumptionDeliveryListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumptionDeliveryList::class);
    }

    // /**
    //  * @return ConsumptionDeliveryList[] Returns an array of ConsumptionDeliveryList objects
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
    public function findOneBySomeField($value): ?ConsumptionDeliveryList
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
