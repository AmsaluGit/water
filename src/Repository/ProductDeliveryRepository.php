<?php

namespace App\Repository;

use App\Entity\ProductDelivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductDelivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductDelivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductDelivery[]    findAll()
 * @method ProductDelivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductDeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductDelivery::class);
    }

    // /**
    //  * @return ProductDelivery[] Returns an array of ProductDelivery objects
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
    public function findOneBySomeField($value): ?ProductDelivery
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
