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
    
    public function findStock($value)
    {
        $qb=$this->createQueryBuilder('p');

        if($value)
            $qb->andWhere("p.name  LIKE '%".$value."%'");
            return 
            $qb->orderBy('p.id', 'ASC')
            ->getQuery()
            
        ;
    }
    public function getMaxSerialNo(){
        $qb=$this->createQueryBuilder('st')
                    ->andWhere('st.id > :ids')
                    ->setParameter('ids', 0)
                    ->orderBy('st.serialNumber', "DESC")
                    ->setMaxResults(1)
                    ->getQuery()
                    ->getResult();

            return $qb;
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
