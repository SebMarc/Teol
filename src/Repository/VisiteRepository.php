<?php

namespace App\Repository;

use App\Entity\Visite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Visite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Visite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Visite[]    findAll()
 * @method Visite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VisiteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Visite::class);
    }

    public function findAllVisiteByTechnicien($t) {      
        return $this->createQueryBuilder('v')
        ->where('v.tech = :tech')
        ->setParameter('tech', $t)
        ->orderBy('v.createdAt', 'DESC')
        ->getQuery()
        ->getResult();
    }

    public function findByPartialSocietyVisiteByTechnicien($term, $t) 
    {
        $qb = $this     ->createQueryBuilder('v')
                        ->innerJoin('v.user', 'vu')
                        ->addSelect('vu')
                        ->where('vu.society LIKE :searchSociety')
                        ->andWhere('v.tech = :tech')
                        ->setParameter('searchSociety',  '%' . $term . '%')
                        ->setParameter('tech', $t)
                        ->orderBy('v.user', 'ASC');

                        return $qb->getQuery()->getResult();
    }
    public function findByDateVisiteByTechnicien($term, $t) 
    {
        $qb = $this     ->createQueryBuilder('v')
                        ->where('v.createdAt LIKE :searchDate')
                        ->andWhere('v.tech = :tech')
                        ->setParameter('searchDate',  '%' . $term . '%')
                        ->setParameter('tech', $t)
                        ->orderBy('v.user', 'ASC');

                        return $qb->getQuery()->getResult();
    }

   
    

    // /**
    //  * @return Visite[] Returns an array of Visite objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('v.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Visite
    {
        return $this->createQueryBuilder('v')
            ->andWhere('v.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
