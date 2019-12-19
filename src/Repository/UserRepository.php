<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * @return Query
     */
    public function findAllClientByTechnicien($t) :Query 
    {
        return $this->createQueryBuilder('u')
        ->where('u.technicien = :tech')
        ->setParameter('tech', $t)
        ->orderBy('u.society', 'ASC')
        ->getQuery();
 
    }

    public function findAllClientByTechnicienQuery($t) 
    {
       $qb =  $this ->createQueryBuilder('u')
                    ->where('u.technicien = :tech')
                    ->setParameter('tech', $t);
                    return $qb->getQuery()->getResult();
     
    }

    public function findAllClientByTechnicienSupervision($t) 
    {
       return $this ->createQueryBuilder('u')
                    ->where('u.technicien = :tech')
                    ->setParameter('tech', $t);
     
    }

    
    /**
     * @return Query
     */
    public function findAllMemberOnly() :Query
    {   
        return $this    ->createQueryBuilder('u')
                        ->where('u.roles LIKE :role')
                        ->setParameter('role','%"'.'ROLE_MEMBER'.'"%')
                        ->orderBy('u.society', 'ASC')
                        ->getQuery();
        
    }

    /**
     * @return Query
     */
    public function findAllMemberOnlyGlobalEncours() :Query {   
        return $this->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role','%"'.'ROLE_MEMBER'.'"%')
        ->orderBy('u.encours', 'ASC')
        ->getQuery();
        
        
    }


    public function findAllTechnicienForm() { 
        return $this->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role','%ROLE_TECH%');
      
        
    }


    /**
     * @return Query
     */
    public function findAllTechnicien() :Query {       
        return $this->createQueryBuilder('u')
        ->where('u.roles LIKE :role')
        ->setParameter('role','%ROLE_TECH%')
        ->orderBy('u.lastname', 'ASC')
        ->getQuery();
        
       
     }

     public function findTechnicienByClient($email) {
        $qb =$this->createQueryBuilder('u')
        ->where('u.email = :email')
        ->setParameter('email', $email)
        ->getQuery()
        ->getResult();
        return $qb;
     }

     /**
     * @return Query
     */
    public function findAllEncoursByTechnicien($t) :Query {      
        return $this->createQueryBuilder('u')
        ->where('u.technicien = :tech')
        ->setParameter('tech', $t)
        ->orderBy('u.encours', 'ASC')
        ->getQuery();
     }

     /**
     * @return Query
     */
    public function findEncoursBySociety($term) :Query {      
        return $this->createQueryBuilder('u')
        ->where('u.society LIKE :searchSociety')
        ->setParameter('searchSociety',  '%' . $term . '%')
        ->getQuery();
     }

    public function findByPartialUserSociety($term) {
         $qb = $this    ->createQueryBuilder('u')
                        ->where('u.society LIKE :searchSociety')
                        ->setParameter('searchSociety',  '%' . $term . '%')
                        ->orderBy('u.society', 'ASC');

                        return $qb->getQuery()->getResult();
     }

    public function findByPartialUserLastname($term) {
        $qb = $this    ->createQueryBuilder('u')
                       ->where('u.lastname LIKE :searchLastname')
                       ->setParameter('searchLastname',  '%' . $term . '%')
                       ->orderBy('u.lastname', 'ASC');

                       return $qb->getQuery()->getResult();
    }

    public function findByPartialSocietyAllClientByTechnicien($term, $t) 
    {
        $qb = $this    ->createQueryBuilder('u')
                        ->where('u.society LIKE :searchSociety')
                        ->andWhere('u.technicien = :tech')
                        ->setParameter('searchSociety',  '%' . $term . '%')
                        ->setParameter('tech', $t)
                        ->orderBy('u.society', 'ASC');

                        return $qb->getQuery()->getResult();
    }

    public function findByPartialLastnameAllClientByTechnicien($term, $t) {
        $qb = $this    ->createQueryBuilder('u')
                       ->where('u.lastname LIKE :searchLastname')
                       ->andWhere('u.technicien = :tech')
                       ->setParameter('searchLastname',  '%' . $term . '%')
                       ->setParameter('tech', $t)
                       ->orderBy('u.lastname', 'ASC');

                       return $qb->getQuery()->getResult();
    }

    public function findByMember($term, $t) {
        $qb = $this    ->createQueryBuilder('u')
                       ->where('u.society = :searchCustomer')
                       ->andWhere('u.technicien = :tech')
                       ->setParameter('tech', $t )
                       ->setParameter('searchCustomer', '%' . $term . '%');
                      

                       return $qb->getQuery()->getResult();
    }

    public function findByPartialSocietyAllSocietyByTechnicien($term, $t) 
    {
        $qb = $this     ->createQueryBuilder('u')
                        ->where('u.society LIKE :searchSociety')
                        ->andWhere('u.technicien = :tech')
                        ->setParameter('tech', $t )
                        ->setParameter('searchSociety', '%' . $term . '%');
   
                        return $qb->getQuery()->getResult();
    }

    



     

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
