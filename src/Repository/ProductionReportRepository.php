<?php

namespace App\Repository;

use App\Entity\ProductionReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ProductionReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionReport[]    findAll()
 * @method ProductionReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionReport::class);
    }
    
    public function findProductionReport($search=null)
    {
        $qb=$this->createQueryBuilder('p');

        if($search)
            $qb->andWhere("p.product  LIKE '%".$search."%'");
            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
            
        ;
    }
    public function findDateRangeResultProduction($start,$last){
      
    $entityManager = $this->getEntityManager();

    $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
                 ->from('App\Entity\ProductionReport', 'm')
                 ->where('m.date <= :last')
                 ->andWhere('m.date >= :start')
                 ->setParameter('start',$start)
                 ->setParameter('last', $last);
        return( $qb->orderBy('m.id', 'ASC')
                  ->getQuery()->getResult());


    }
    public function findDateIntervalProduction($range){
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
                 ->from('App\Entity\ProductionReport', 'm')
                 ->where('m.date >= :last')
                 ->setParameter('last', new \DateTime('-'.$range.' month'));
        return $qb->orderBy('m.id', 'ASC')
                  ->getQuery()->getResult();
}
    // /**
    //  * @return ProductionReport[] Returns an array of ProductionReport objects
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
    public function findOneBySomeField($value): ?ProductionReport
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
