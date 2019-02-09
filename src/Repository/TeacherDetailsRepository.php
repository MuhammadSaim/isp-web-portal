<?php

namespace App\Repository;

use App\Entity\TeacherDetails;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeacherDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherDetails[]    findAll()
 * @method TeacherDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherDetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeacherDetails::class);
    }

    // /**
    //  * @return TeacherDetails[] Returns an array of TeacherDetails objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TeacherDetails
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
