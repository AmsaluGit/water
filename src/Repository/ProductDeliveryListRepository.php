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
    

    public function findProductDelivery($search = null)
    {
        $qb=$this->createQueryBuilder('p')
                 ->select('p , c')
                 ->join('p.productDelivery','c')
                 ->where('p.id = val')
                 ->setParameter('val',$search)
                 ;
                 

        if($search)
            $qb->andWhere("c = val ")
                ->setParameter("val",$search)
              // ->andwhere("c.name  LIKE '%".$search2."%'")
               ;

               return 
            $qb->orderBy('p.id', 'ASC')
               ->getQuery() 
            
        ;
    }
        
    public function findProductDeliveryList($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.product = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
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
