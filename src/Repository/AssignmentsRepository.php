<?php

namespace App\Repository;

use App\Entity\Assignments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Assignments|null find($id, $lockMode = null, $lockVersion = null)
 * @method Assignments|null findOneBy(array $criteria, array $orderBy = null)
 * @method Assignments[]    findAll()
 * @method Assignments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AssignmentsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Assignments::class);
    }

    // /**
    //  * @return Assignments[] Returns an array of Assignments objects
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
    public function findOneBySomeField($value): ?Assignments
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getTopAssignmentsOfTeacher($user, $session, $numberOfLectures = 10)
    {
        return $this->createQueryBuilder('a')
            ->where('a.teacher = :teacherId')
            ->andWhere('a.session = :session')
            ->setParameter('session', $session)
            ->setParameter('teacherId', $user)
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($numberOfLectures)
            ->getQuery()
            ->getResult();
    }

    public function getTopAssignmentsOfStudents($user, $session, $numberOfLectures = 5)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.startDate <= :currentDate')
            ->andWhere('a.department = :department')
            ->andWhere('a.program = :program')
            ->andWhere('a.semester = :semester')
            ->andWhere('a.session = :session')
            ->setParameter('session', $session)
            ->setParameter('currentDate', new \DateTime)
            ->setParameter('department', $user->getDepartment())
            ->setParameter('program', $user->getProgram())
            ->setParameter('semester', $user->getSemester())
            ->orderBy('a.createdAt', 'DESC')
            ->setMaxResults($numberOfLectures)
            ->getQuery()
            ->getResult();
    }

    public function getAssignment($assignment, $user)
    {
        return $this->createQueryBuilder('a')
                    ->select('a, sub')
                    ->join('a.assignmentSubmissions', 'sub')
                    ->where('sub.student = :user')
                    ->andWhere('sub.student = :user1')
                    ->andWhere('a.slug = :assigment')
                    ->setParameter('assigment', $assignment)
                    ->setParameter('user', $user)
                    ->setParameter('user1', $user)
                    ->getQuery()
                    ->getResult();
    }
    
}
