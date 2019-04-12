<?php

namespace App\Repository;

use App\Entity\Examination;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Examination|null find($id, $lockMode = null, $lockVersion = null)
 * @method Examination|null findOneBy(array $criteria, array $orderBy = null)
 * @method Examination[]    findAll()
 * @method Examination[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExaminationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Examination::class);
    }

    // /**
    //  * @return Examination[] Returns an array of Examination objects
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
    public function findOneBySomeField($value): ?Examination
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findFinalResults(array $param)
    {
        $qb = $this->createQueryBuilder('e');
        return   $qb->andWhere('e.department = :department')
                    ->andWhere('e.program = :program')
                    ->andWhere('e.course = :course')
                    ->andWhere('e.semester = :semester')
                    ->andWhere('e.section = :section')
                    ->andWhere('e.session = :session')
                    ->andWhere('e.sestional != :sestional')
                    ->andWhere('e.final != :final')
                    ->setParameter('department', $param['department'])
                    ->setParameter('program', $param['program'])
                    ->setParameter('course', $param['course'])
                    ->setParameter('semester', $param['semester'])
                    ->setParameter('section', $param['section'])
                    ->setParameter('session', $param['session'])
                    ->setParameter('sestional', '')
                    ->setParameter('final', '')
                    ->getQuery()
                    ->getResult();
    }
}
