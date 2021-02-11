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

    public function findRequester($search=null)
    {
        $qb=$this->createQueryBuilder('c')
                ->select('c, u, p')
                ->join('c.requester', 'u')
                ->where("u.id = c.requester")
                ->join('c.product', 'p')
                ->where("p.id = c.product");
                


        if($search)
            $qb->andWhere("u.firstName LIKE '%".$search."%'")
               ->orWhere("u.middleName LIKE '%".$search."%'")
               ->orWhere("u.lastName LIKE '%".$search."%'")
               ;
            return $qb->orderBy('u.id', 'ASC')
                      ->getQuery()
            ;
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
