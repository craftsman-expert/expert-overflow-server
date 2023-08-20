<?php

namespace App\Repository;

use App\Entity\QuestionComment;
use App\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionComment>
 *
 * @method QuestionComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method QuestionComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method QuestionComment[] findAll()
 * @method QuestionComment[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionComment::class);
    }

    public function findWithOffset(
        int $questionId,
        int $offset = 0,
        int $limit = 100,
        string $order = 'asc',
    ): Paginator {
        $qb = $this->createQueryBuilder('qc');

        $qb->leftJoin('qc.question', 'question');
        $qb->leftJoin('qc.author', 'author');
        $qb->addSelect('author');

        $qb->andWhere('question.id = :questionId');
        $qb->setParameter('questionId', $questionId);

        $qb->addOrderBy('qc.id', $order);

        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

    public function findLasts(
        int $questionId,
        int $limit = 100
    ): Paginator
    {
        $qb = $this->createQueryBuilder('qc');

        $qb->leftJoin('qc.question', 'question');
        $qb->leftJoin('qc.author', 'author');
        $qb->addSelect('author');

        $qb->andWhere('question.id = :questionId');
        $qb->setParameter('questionId', $questionId);

        $qb->addOrderBy('qc.id', 'DESC');

        $qb->setFirstResult(0);
        $qb->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

//    public function findOneBySomeField($value): ?QuestionComment
//    {
//        return $this->createQueryBuilder('q')
//            ->andWhere('q.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
