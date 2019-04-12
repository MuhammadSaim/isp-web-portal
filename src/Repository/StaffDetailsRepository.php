<?php

namespace App\Repository;

use App\Entity\StaffDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StaffDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method StaffDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffDetails[]    findAll()
 * @method StaffDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffDetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StaffDetails::class);
    }

    // /**
    //  * @return StaffDetails[] Returns an array of StaffDetails objects
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
    public function findOneBySomeField($value): ?StaffDetails
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
