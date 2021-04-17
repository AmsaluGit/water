<?php

namespace App\Repository;

use App\Entity\Stock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Stock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Stock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Stock[]    findAll()
 * @method Stock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StockRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Stock::class);
    }

    /**
     * @return Stock[] Returns an array of Stock objects
     */
    
    // public function findStock($value)
    // {
    //     $qb=$this->createQueryBuilder('p');

    //     if($value)
    //         $qb->andWhere("p.name  LIKE '%".$value."%'");
    //         return 
    //         $qb->orderBy('p.id', 'ASC')
    //         ->getQuery()
            
    //     ;
    // }
    public function findStock($search=null)
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

    /*
    public function findOneBySomeField($value): ?Stock
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
