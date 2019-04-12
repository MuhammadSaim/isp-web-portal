<?php

namespace App\Repository;

use App\Entity\ExamsStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ExamsStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ExamsStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ExamsStatus[]    findAll()
 * @method ExamsStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamsStatusRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ExamsStatus::class);
    }

    // /**
    //  * @return ExamsStatus[] Returns an array of ExamsStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ExamsStatus
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findStatusMid()
    {
        return $this->createQueryBuilder('e')
                    ->where('e.exam = :mid')
                    ->andWhere('e.status = :status')
                    ->setParameter('mid', 'mid')
                    ->setParameter('status', '1')
                    ->getQuery()
                    ->getOneOrNullResult();
    }

    public function findStatusFinal()
    {
        return $this->createQueryBuilder('e')
            ->where('e.exam = :mid')
            ->andWhere('e.status = :status')
            ->setParameter('mid', 'final')
            ->setParameter('status', '1')
            ->getQuery()
            ->getOneOrNullResult();
    }
}
