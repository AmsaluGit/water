<?php

namespace App\Repository;

use App\Entity\Product;
use App\Entity\Stock;
use App\Entity\ConsumptionRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Balance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Balance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Balance[]    findAll()
 * @method Balance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BalanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumptionRequest::class) ;
    
       
    }

    public function findBalance($search=null)
    {
        $qb=$this->createQueryBuilder('p')
                  
                 
                 ->select('p , c')
                 ->join('p.product','c')
                 ->Where("c.id=p.product")

                 ->andWhere("p.quantity  LIKE '%".$search."%'")

               /* 
                 ->join('p.stock','d')
                 ->where("d.id=p.stock")
/*
                 ->join('p.product','e')
                 ->where("e.id=p.product")
*/
                 ;


        if($search)
            $qb->andWhere("p.name  LIKE '%".$search."%'");
            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
            
        ;
    }
    public function findForUserGroup($usergroup=null)
    {
        $qb=$this->createQueryBuilder('p');
        
        if (sizeof($usergroup)) {

            $qb->andWhere('p.id not in ( :usergroup )')
                ->setParameter('usergroup', $usergroup);
        }
       

             
            return $qb->orderBy('p.id', 'ASC')
            ->getQuery()->getResult()
     
        ;
      
    }

    // /**
    //  * @return Store[] Returns an array of Store objects
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
    public function findOneBySomeField($value): ?Store
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
