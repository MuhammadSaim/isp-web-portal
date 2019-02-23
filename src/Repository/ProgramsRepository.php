<?php

namespace App\Repository;

use App\Entity\Programs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Programs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Programs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Programs[]    findAll()
 * @method Programs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProgramsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Programs::class);
    }

    // /**
    //  * @return Programs[] Returns an array of Programs objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Programs
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findValueWithDepart($departmentId, $programId)
    {
        return $this->createQueryBuilder('p')
                    ->andWhere('p.department = :department')
                    ->andWhere('p.id = :program')
                    ->setParameter('department', $departmentId)
                    ->setParameter('program', $programId)
                    ->getQuery()
                    ->getOneOrNullResult();
    }
}
