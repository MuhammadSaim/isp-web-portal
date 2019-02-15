<?php

namespace App\Repository;

use App\Entity\Lectures;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lectures|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lectures|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lectures[]    findAll()
 * @method Lectures[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LecturesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lectures::class);
    }

    // /**
    //  * @return Lectures[] Returns an array of Lectures objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lectures
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
