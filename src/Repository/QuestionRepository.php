<?php

namespace App\Repository;

use App\Entity\Question;
use App\ORM\Tools\Pagination\Paginator;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 *
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[] findAll()
 * @method Question[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    public function findByCriteria(
        string $q = '',
        int $offset = 0,
        int $limit = 50,
    ): Paginator {
        $qb = $this->createQueryBuilder('qs');

        $qb->leftJoin('qs.tags', 'tags');
        $qb->addSelect('tags');

        $qb->addOrderBy('qs.id', 'ASC');

        $qb->setFirstResult($offset);
        $qb->setMaxResults($limit);

        return new Paginator($qb->getQuery());
    }

//    public function findOneBySomeField($value): ?Post
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
