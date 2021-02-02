<?php

namespace App\Repository;

use App\Entity\UnitOfMeasure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UnitOfMeasure|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnitOfMeasure|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnitOfMeasure[]    findAll()
 * @method UnitOfMeasure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitOfMeasureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitOfMeasure::class);
    }

    // /**
    //  * @return UnitOfMeasure[] Returns an array of UnitOfMeasure objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?UnitOfMeasure
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
