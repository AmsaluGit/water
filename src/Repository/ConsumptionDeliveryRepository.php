<?php

namespace App\Repository;

use App\Entity\ConsumptionDelivery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ConsumptionDelivery|null find($id, $lockMode = null, $lockVersion = null)
 * @method ConsumptionDelivery|null findOneBy(array $criteria, array $orderBy = null)
 * @method ConsumptionDelivery[]    findAll()
 * @method ConsumptionDelivery[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConsumptionDeliveryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ConsumptionDelivery::class);
    }
    public function findDelivery($search=null)
    {
        $qb=$this->createQueryBuilder('cd')
                ->select('cd, u')
                ->join('cd.receiver', 'u')
                ->where("u.id = cd.receiver");
                // ->where("u.id = cd.deliveredBy")
                // ->where("u.id = cd.approvedBy");
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

    public function getMaxSerialNo(){
        $qb=$this->createQueryBuilder('d')
                    ->orderBy('d.serialNo', "DESC")
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getResult();

        return $qb;
    }

    // /**
    //  * @return ConsumptionDelivery[] Returns an array of ConsumptionDelivery objects
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
    public function findOneBySomeField($value): ?ConsumptionDelivery
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
