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

     
    public function findProduct($search=null)
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
    public function findDateRangeResult($start,$last, $val){
        // $query = $this->createQueryBuilder('p')
        //     ->select('p')
        //     ->where('p.delivery_date >= :start')
        //     ->andWhere('p.delivery_date  :end')
        //     // ->orderBy('p.id', 'ASC')
        //     ->setParameter('start', $from)
        //     ->setParameter('end', $to)
        //     ->where($from,$to);

        //     return $query->orderBy('p.id', 'ASC')
        //     ->getQuery()
        //     // ->getResult()
        //         ;


    //  $entityManager = $this->getEntityManager();
    //     $query = $entityManager->createQuery(
    //         'SELECT p,q, q_list
    //         FROM App\Entity\ProductDelivery p
    //         FROM App\Entity\ProductDeliveryList q_list
    //         WHERE  q.type = :val
    //         JOIN    q_list.product , m
    //         WHERE p.deliveryDate
    //         BETWEEN :start_d AND :end_d

    //         ORDER BY p.id ASC'
    //     )->setParameter('start_d', $from)
    //     ->setParameter('end_d', $to);
    //     return $query->getResult();
    $entityManager = $this->getEntityManager();

    $qb = $entityManager->createQueryBuilder();
        $qb->select('m, p, p_list')
                 ->from('App\Entity\ProductDelivery', 'm')
                //  ->from('App\Entity\Product', 'p')
                 ->from('App\Entity\ProductDeliveryList', 'p_list')
                 ->where("p.type = :val")
                 ->join('p_list.product', 'p')
                 ->andWhere('m.deliveryDate < :last')
                 ->andWhere('m.deliveryDate > :start')
                 ->setParameter('val',$val)
                 ->setParameter('start',$start)
                 ->setParameter('last', $last);
        dd( $qb->orderBy('m.id', 'ASC')
                  ->getQuery()->getResult());


    }
    public function findDateInterval($today, $range,$val){
        $entityManager = $this->getEntityManager();

        // $query = $entityManager->createQuery(
        //     'SELECT p
        //     FROM App\Entity\ProductDelivery p
        //     WHERE p.deliveryDate
        //     BETWEEN
        //     DATE_DIFF ( CURRENT_DATE()  
        //     , :today )

        //     -- ORDER BY p.id ASC'
        // )
        // ->setParameter('size', $range)
        // ->setParameter('today', $today)
        // ;
        // return $query->getResult();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('m, p, p_list')
                 ->from('App\Entity\ProductDelivery', 'm')
                //  ->from('App\Entity\Product', 'p')
                 ->from('App\Entity\ProductDeliveryList', 'p_list')
                 ->where("p.type = :val")
                 ->join('p_list.product', 'p')
                 ->andWhere('m.deliveryDate > :last')
                 ->setParameter('val',$val)
                 ->setParameter('last', new \DateTime('-'.$range.' month'));
        return $qb->orderBy('m.id', 'ASC')
                  ->getQuery()->getResult();





        // SELECT * FROM dt_table WHERE `date` BETWEEN DATE_SUB( CURDATE( ) ,INTERVAL 6 MONTH ) AND DATE_SUB( CURDATE( ) ,INTERVAL 3 MONTH )
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

    
    public function findBiggestSerial()
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.id > :ids')
            ->setParameter('ids', 0)
            ->orderBy('p.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult()
        ;
    }
    
    
}
