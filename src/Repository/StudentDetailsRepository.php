<?php

namespace App\Repository;

use App\Entity\StudentDetails;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method StudentDetails|null find($id, $lockMode = null, $lockVersion = null)
 * @method StudentDetails|null findOneBy(array $criteria, array $orderBy = null)
 * @method StudentDetails[]    findAll()
 * @method StudentDetails[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentDetailsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, StudentDetails::class);
    }

    // /**
    //  * @return StudentDetails[] Returns an array of StudentDetails objects
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
    public function findOneBySomeField($value): ?StudentDetails
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getStudentRecord($user)
    {
        return $this->createQueryBuilder('sr')
                    ->select('sr, u')
                    ->leftJoin('sr.user', 'u')
                    ->where('sr.user = :user')
                    ->setParameter('user', $user)
                    ->getQuery()
                    ->getOneOrNullResult();

    }

    public function findRegNo($regNo, $id)
    {
        return $this->createQueryBuilder('u')
            -> andWhere('u.regNo = :regNo')
            ->andWhere('u.user != :id')
            ->setParameter('email', $regNo)
            ->setParameter("id", $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
