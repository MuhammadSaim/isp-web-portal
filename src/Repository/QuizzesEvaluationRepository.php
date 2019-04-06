<?php

namespace App\Repository;

use App\Entity\QuizzesEvaluation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method QuizzesEvaluation|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuizzesEvaluation|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuizzesEvaluation[]    findAll()
 * @method QuizzesEvaluation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizzesEvaluationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, QuizzesEvaluation::class);
    }

    // /**
    //  * @return QuizzesEvaluation[] Returns an array of QuizzesEvaluation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('q.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?QuizzesEvaluation
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
