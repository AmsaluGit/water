<?php

namespace App\Repository;

use App\Entity\Sells;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Sells|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sells|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sells[]    findAll()
 * @method Sells[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SellsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sells::class);
    }

    
    public function findSells($search=null)
    {
        $qb=$this->createQueryBuilder('c')
                ->select('c, u')
                ->join('c.receivedBy', 'u')
                ->where("u.id = c.receivedBy");
                // ->join('c.product', 'p')
                // ->where("p.id = c.product");
                


        if($search)
            $qb->andWhere("u.firstName LIKE '%".$search."%'")
               ->orWhere("u.middleName LIKE '%".$search."%'")
               ->orWhere("u.lastName LIKE '%".$search."%'")
               ;
            return $qb->orderBy('u.id', 'ASC')
                      ->getQuery()
            ;
    }
    public function getMaxSerialNo(){
        $qb=$this->createQueryBuilder('s')
                    ->andWhere('s.id > :ids')
                    ->setParameter('ids', 0)
                    ->orderBy('s.serialNumber', "DESC")
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getResult();

         return $qb;
    }

    // /**
    //  * @return Sells[] Returns an array of Sells objects
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
    public function findOneBySomeField($value): ?Sells
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
