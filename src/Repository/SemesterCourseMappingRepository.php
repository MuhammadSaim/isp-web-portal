<?php

namespace App\Repository;

use App\Entity\SemesterCourseMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SemesterCourseMapping|null find($id, $lockMode = null, $lockVersion = null)
 * @method SemesterCourseMapping|null findOneBy(array $criteria, array $orderBy = null)
 * @method SemesterCourseMapping[]    findAll()
 * @method SemesterCourseMapping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SemesterCourseMappingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SemesterCourseMapping::class);
    }

    // /**
    //  * @return SemesterCourseMapping[] Returns an array of SemesterCourseMapping objects
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
    public function findOneBySomeField($value): ?SemesterCourseMapping
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function recordExists($departmentId, $programId, $semesterId)
    {
        return $this->createQueryBuilder('s')
                    ->andWhere('s.department = :department')
                    ->andWhere('s.program = :program')
                    ->andWhere('s.semester = :semester')
                    ->setParameter('department', $departmentId)
                    ->setParameter('program', $programId)
                    ->setParameter('semester', $semesterId)
                    ->getQuery()
                    ->getOneOrNullResult();
    }

}
