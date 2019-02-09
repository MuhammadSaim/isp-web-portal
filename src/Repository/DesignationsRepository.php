<?php

namespace App\Repository;

use App\Entity\Designations;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Designations|null find($id, $lockMode = null, $lockVersion = null)
 * @method Designations|null findOneBy(array $criteria, array $orderBy = null)
 * @method Designations[]    findAll()
 * @method Designations[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DesignationsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Designations::class);
    }

    // /**
    //  * @return Designations[] Returns an array of Designations objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Designations
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
