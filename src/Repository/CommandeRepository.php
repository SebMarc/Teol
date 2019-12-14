<?php

namespace App\Repository;

use App\Entity\Commande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;

/**
 * @method Commande|null find($id, $lockMode = null, $lockVersion = null)
 * @method Commande|null findOneBy(array $criteria, array $orderBy = null)
 * @method Commande[]    findAll()
 * @method Commande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Commande::class);
    }

    /**
     * @return Query
     */
    public function findAllOrderByTechnicien($t) :Query //Ok
    {
        return $this->createQueryBuilder('c')
        ->where('c.tech = :tech')
        ->setParameter('tech', $t)
        ->orderBy('c.createdAt', 'DESC')
        ->getQuery();  
    }

  

     /**
     * @return Query
     */
    public function findAllOrderByClient($t) :Query //Ok
    {
        return $this->createQueryBuilder('c')
        ->where('c.customer = :cust')
        ->setParameter('cust', $t)
        ->orderBy('c.createdAt', 'DESC')
        ->getQuery();  
    }

    /**
     * @return Query
     */
    public function findAllOrderPerOneClient($t) :Query //Ok
    {
        return $this->createQueryBuilder('c')
        ->where('c.customer = :cust')
        ->setParameter('cust', $t)
        ->getQuery();
    }

    public function findByOrderNumber($term, $t) {
        $qb = $this    ->createQueryBuilder('c')
                       ->where('c.number LIKE :searchOrder')
                       ->andWhere('c.tech = :tech')
                       ->setParameter('tech', $t )
                       ->setParameter('searchOrder', $term );
                      
                       return $qb->getQuery()->getResult();
    }

    public function findByMember($term, $t)
     {
        $qb = $this     ->createQueryBuilder('c')
                        ->innerJoin('c.customer', 'cu')
                        ->addSelect('cu')
                        ->where('cu.society LIKE :searchCustomer')
                        ->andWhere('c.tech = :tech')
                        ->setParameter('searchCustomer', '%' . $term . '%')
                        ->setParameter('tech', $t )
                        ->orderBy('c.number', 'DESC');
                      
                       return $qb->getQuery()->getResult();
    }

    public function findByDateOrderByTechnicien($term, $t) 
    {
        $qb = $this     ->createQueryBuilder('c')
                        ->where('c.createdAt LIKE :searchDate')
                        ->andWhere('c.tech = :tech')
                        ->setParameter('searchDate',  '%' . $term . '%')
                        ->setParameter('tech', $t)
                        ->orderBy('c.customer', 'ASC');

                        return $qb->getQuery()->getResult();
    }

    // /**
    //  * @return Commande[] Returns an array of Commande objects
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
    public function findOneBySomeField($value): ?Commande
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
