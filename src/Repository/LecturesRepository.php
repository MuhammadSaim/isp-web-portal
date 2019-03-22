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

    public function getTopLecturesOfStudents($user, $numberOfLectures = 5)
    {
        return $this->createQueryBuilder('l')
                    ->andWhere('l.department = :department')
                    ->andWhere('l.program = :program')
                    ->andWhere('l.semester = :semester')
                    ->setParameter('department', $user->getDepartment())
                    ->setParameter('program', $user->getProgram())
                    ->setParameter('semester', $user->getSemester())
                    ->orderBy('l.createdAt', 'DESC')
                    ->setMaxResults($numberOfLectures)
                    ->getQuery()
                    ->getResult();
    }

    public function getTopLecturesOfTeacher($user, $numberOfLectures = 5)
    {
        return $this->createQueryBuilder('l')
                    ->where('l.teacher = :teacherId')
                    ->setParameter('teacherId', $user)
                    ->orderBy('l.createdAt', 'DESC')
                    ->setMaxResults($numberOfLectures)
                    ->getQuery()
                    ->getResult();
    }
}
