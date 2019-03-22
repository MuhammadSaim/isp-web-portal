<?php

namespace App\Repository;

use App\Entity\TeacherCourseMapping;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TeacherCourseMapping|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeacherCourseMapping|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeacherCourseMapping[]    findAll()
 * @method TeacherCourseMapping[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeacherCourseMappingRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TeacherCourseMapping::class);
    }

    // /**
    //  * @return TeacherCourseMapping[] Returns an array of TeacherCourseMapping objects
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
    public function findOneBySomeField($value): ?TeacherCourseMapping
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getDistinctProgram($teacherId)
    {
       $manager = $this->getEntityManager();
       $query = $manager->createQuery(
           'SELECT DISTINCT(t.program) as program 
           FROM 
           App\Entity\TeacherCourseMapping t
           WHERE t.teacher = :teacherId'
       )->setParameter('teacherId', $teacherId);
       return $query->getResult();
    }

    public function getDistinctDepartment($teacherId)
    {
        $manager = $this->getEntityManager();
        $query = $manager->createQuery(
            'SELECT DISTINCT(t.department) as department 
           FROM 
           App\Entity\TeacherCourseMapping t
           WHERE t.teacher = :teacherId'
        )->setParameter('teacherId', $teacherId);
        return $query->getResult();
    }

    public function getDistinctCourses($teacherId)
    {
        $manager = $this->getEntityManager();
        $query = $manager->createQuery(
            'SELECT DISTINCT(t.course) as course 
           FROM 
           App\Entity\TeacherCourseMapping t
           WHERE t.teacher = :teacherId'
        )->setParameter('teacherId', $teacherId);
        return $query->getResult();
    }

    public function getDistinctSemester($teacherId)
    {
        $manager = $this->getEntityManager();
        $query = $manager->createQuery(
            'SELECT DISTINCT(t.semester) as semester 
           FROM 
           App\Entity\TeacherCourseMapping t
           WHERE t.teacher = :teacherId'
        )->setParameter('teacherId', $teacherId);
        return $query->getResult();
    }

    public function getDistinctSection($teacherId)
    {
        $manager = $this->getEntityManager();
        $query = $manager->createQuery(
            'SELECT DISTINCT(t.section) as section 
           FROM 
           App\Entity\TeacherCourseMapping t
           WHERE t.teacher = :teacherId'
        )->setParameter('teacherId', $teacherId);
        return $query->getResult();
    }
}
