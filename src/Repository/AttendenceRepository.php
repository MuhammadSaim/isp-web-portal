<?php

namespace App\Repository;

use App\Entity\Attendence;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Attendence|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attendence|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attendence[]    findAll()
 * @method Attendence[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendenceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Attendence::class);
    }

    // /**
    //  * @return Attendence[] Returns an array of Attendence objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Attendence
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
