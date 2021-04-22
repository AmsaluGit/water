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
    public function findMaterialReport($search=null)
    {
        $qb=$this->createQueryBuilder('p');

        if($search)
            $qb->andWhere("p.product  LIKE '%".$search."%'");
            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
            
        ;
    }
    public function findDateRangeResultMaterial($start,$last){
      
    $entityManager = $this->getEntityManager();

    $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
                 ->from('App\Entity\MaterialRecord', 'm')
                 ->where('m.date <= :last')
                 ->andWhere('m.date >= :start')
                 ->setParameter('start',$start)
                 ->setParameter('last', $last);
        return( $qb->orderBy('m.date', 'ASC')
                  ->getQuery()->getResult());


    }
    public function findDateIntervalMaterial($range){
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
                 ->from('App\Entity\MaterialRecord', 'm')
                 ->where('m.date >= :last')
                 ->setParameter('last', new \DateTime('-'.$range.' month'));
        return $qb->orderBy('m.date', 'ASC')
                  ->getQuery()->getResult();
        }
    public function IntervalSumMaterial($range,$val){
        $entityManager = $this->getEntityManager();

        $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
               ->select('SUM(m.quantity) as totalSum')
                 ->from('App\Entity\MaterialRecord', 'm')
                 ->where('m.date >= :last')
                 ->andWhere('m.product = :val')
                 ->setParameter('val',$val)
                 ->setParameter('last', new \DateTime('-'.$range.' month'));
        return $qb->orderBy('m.id', 'ASC')
                  ->getQuery()->getResult();

        }
    public function RangeSumMaterial($start, $last ,$val){

    $entityManager = $this->getEntityManager();

    $qb = $entityManager->createQueryBuilder();
        $qb->select('m')
                ->select('SUM(m.quantity) as totalSum')
                 ->from('App\Entity\MaterialRecord', 'm')
                 ->where('m.date <= :last')
                 ->andWhere('m.date >= :start')
                 ->andWhere('m.product =:val')
                 ->setParameter('val',$val)
                 ->setParameter('start',$start)
                 ->setParameter('last', $last);
        return( $qb->orderBy('m.id', 'ASC')
                  ->getQuery()->getResult());



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
