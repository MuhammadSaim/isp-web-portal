<?php

namespace App\Repository;

use App\Entity\Semesters;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Semesters|null find($id, $lockMode = null, $lockVersion = null)
 * @method Semesters|null findOneBy(array $criteria, array $orderBy = null)
 * @method Semesters[]    findAll()
 * @method Semesters[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemestersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Semesters::class);
    }

    // /**
    //  * @return Semesters[] Returns an array of Semesters objects
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
    public function findOneBySomeField($value): ?Semesters
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
