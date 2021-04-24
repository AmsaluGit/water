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
                ->select('c, u, s')
                ->join('c.requester', 'u')
                ->where("u.id = c.requester")
                ->join('c.section','s')
                ->where('c.section = s.id');
                // ->join('c.consumptionRequestLists', 'cl')
                // ->where('c.consumptionRequestLists = 'cl');
                // // ->join('c.section', 's')
                // ->where("c.section = s.id");
                


        if($search)
            $qb->andWhere("u.firstName LIKE '%".$search."%'")
               ->orWhere("u.middleName LIKE '%".$search."%'")
               ->orWhere("u.lastName LIKE '%".$search."%'")
               ;
            return $qb->orderBy('u.id', 'ASC')
                      ->getQuery()
            ;
    }

    public function findByApprovalStatus()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.approvalStatus != :1')
            ->andWhere('c.approvalStatus != :2')
            // ->setParameter('val', 2)
            // ->orderBy('c.id', 'ASC')
            // ->setMaxResults(10)
            ->getQuery()
            // ->getResult()
        ;
    }

    public function getMaxSerialNo(){
        $qb=$this->createQueryBuilder('c')
                    ->andWhere('c.id > :ids')
                    ->setParameter('ids', 0)
                    ->orderBy('c.serialNo', "DESC")
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getResult();

        return $qb;
        
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
