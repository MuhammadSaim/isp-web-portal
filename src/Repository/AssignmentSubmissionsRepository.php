<?php

namespace App\Repository;

use App\Entity\AssignmentSubmissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method AssignmentSubmissions|null find($id, $lockMode = null, $lockVersion = null)
 * @method AssignmentSubmissions|null findOneBy(array $criteria, array $orderBy = null)
 * @method AssignmentSubmissions[]    findAll()
 * @method AssignmentSubmissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignmentSubmissionsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, AssignmentSubmissions::class);
    }

    // /**
    //  * @return AssignmentSubmissions[] Returns an array of AssignmentSubmissions objects
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
    public function findOneBySomeField($value): ?AssignmentSubmissions
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
