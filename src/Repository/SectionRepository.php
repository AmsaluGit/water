<?php

namespace App\Repository;

use App\Entity\Section;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Section|null find($id, $lockMode = null, $lockVersion = null)
 * @method Section|null findOneBy(array $criteria, array $orderBy = null)
 * @method Section[]    findAll()
 * @method Section[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Section::class);
    }

    public function findSection($search=null)
    {
        $qb=$this->createQueryBuilder('s')
                 ->select('s, d')
                 ->join('s.department', 'd')
                 ->where("d.id = s.department");
        if($search)
            $qb->andWhere("s.name  LIKE '%".$search."%'");
            return $qb->orderBy('s.id', 'ASC')
                      ->getQuery()
            ;
    }

//     $qb=$this->createQueryBuilder('c')
//     ->select('c, u, s')
//     ->join('c.requester', 'u')
//     ->where("u.id = c.requester")
//     ->join('c.section','s')
//     ->where('c.section = s.id');
//     // ->join('c.consumptionRequestLists', 'cl')
//     // ->where('c.consumptionRequestLists = 'cl');
//     // // ->join('c.section', 's')
//     // ->where("c.section = s.id");
    


// if($search)
// $qb->andWhere("u.firstName LIKE '%".$search."%'")
//    ->orWhere("u.middleName LIKE '%".$search."%'")
//    ->orWhere("u.lastName LIKE '%".$search."%'")
//    ;
// return $qb->orderBy('u.id', 'ASC')
//           ->getQuery()
// ;
// }


    // /**
    //  * @return Section[] Returns an array of Section objects
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
    public function findOneBySomeField($value): ?Section
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
