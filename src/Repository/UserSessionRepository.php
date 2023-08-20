<?php

namespace App\Repository;

use App\Entity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Entity\UserSession|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entity\UserSession|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entity\UserSession[] findAll()
 * @method Entity\UserSession[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserSessionRepository extends ServiceEntityRepository
{
    /**
     * UserRepository constructor.
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entity\UserSession::class);
    }

    /**
     * Вернёт сессии указанного пользователя.
     *
     * @param string $id идентификатор пользователя системы
     * @param int $offset смещение, необходимое для выборки определенного подмножества сессий
     * @param int $count Количество сессий, которое необходимо получить.
     *                   Положительное число, по умолчанию 50.
     */
    public function getByUserId(string $id, int $offset = 0, int $count = 50): Paginator
    {
        $qb = $this->createQueryBuilder('us');

        $qb = $qb->andWhere('us.user = :user_id');
        $qb = $qb->setParameter('user_id', $id);

        $qb = $qb->addOrderBy('us.id', 'DESC');

        $qb = $qb->setFirstResult($offset);
        $qb = $qb->setMaxResults($count);

        $paginator = new Paginator($qb->getQuery());
        $paginator->setUseOutputWalkers(false);

        return $paginator;
    }
}
