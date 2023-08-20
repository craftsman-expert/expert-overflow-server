<?php

namespace App\Repository;

use App\Entity\AnswerComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AnswerComment>
 *
 * @method AnswerComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerComment[] findAll()
 * @method AnswerComment[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerComment::class);
    }

//    /**
//     * @return AnswerComment[] Returns an array of AnswerComment objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AnswerComment
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
