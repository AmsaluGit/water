<?php

namespace App\Repository;

use App\Entity\UnitOfMeasure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UnitOfMeasure|null find($id, $lockMode = null, $lockVersion = null)
 * @method UnitOfMeasure|null findOneBy(array $criteria, array $orderBy = null)
 * @method UnitOfMeasure[]    findAll()
 * @method UnitOfMeasure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UnitOfMeasureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UnitOfMeasure::class);
    }

    public function findUnitOfMeasure($search=null)
    {
        $qb=$this->createQueryBuilder('p');

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
}
