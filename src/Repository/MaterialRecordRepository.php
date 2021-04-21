<?php

namespace App\Repository;

use App\Entity\MaterialRecord;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MaterialRecord|null find($id, $lockMode = null, $lockVersion = null)
 * @method MaterialRecord|null findOneBy(array $criteria, array $orderBy = null)
 * @method MaterialRecord[]    findAll()
 * @method MaterialRecord[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MaterialRecordRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MaterialRecord::class);
    }

    // /**
    //  * @return MaterialRecord[] Returns an array of MaterialRecord objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MaterialRecord
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findMaterialRecord($search = null)
    {
        $qb=$this->createQueryBuilder('s')
                 ->select('s')
                 ;
                 

        if($search)
            $qb->andWhere("s.product  LIKE '%".$search."%'")
              // ->andwhere("c.name  LIKE '%".$search2."%'")
               ;

               return 
            $qb->orderBy('s.id', 'ASC')
               ->getQuery()
            
        ;
    }
}
