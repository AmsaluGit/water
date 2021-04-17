<?php

namespace App\Repository;

use App\Entity\ProductDeliveryList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductDeliveryList|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDeliveryList|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDeliveryList[]    findAll()
 * @method ProductDeliveryList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDeliveryListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDeliveryList::class);
    }

    // /**
    //  * @return ProductDeliveryList[] Returns an array of ProductDeliveryList objects
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
    public function findOneBySomeField($value): ?ProductDeliveryList
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
