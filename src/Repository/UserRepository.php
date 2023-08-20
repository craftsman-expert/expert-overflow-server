<?php

namespace App\Repository;

use App\Entity\SocialNetwork;
use App\Entity\User;
use App\Entity\UserSocialNetwork;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @implements PasswordUpgraderInterface<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[] findAll()
 * @method User[] findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Найти пользователя по социальной сети.
     *
     * @param string $socialNetwork социальная сеть
     * @param string $externalId внешний идентификатор пользователя в социальной сети
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUserBySocialNetwork(string $socialNetwork, string $externalId): User|null
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u');

        $qb->leftJoin('u.socialNetworks', 'usn'); // usn - User social network
        $qb->leftJoin('usn.socialNetwork', 'sn'); // sn - Social network

        $qb->andWhere('sn.key = :socialNetwork');
        $qb->andWhere('usn.externalId = :externalId');

        $qb->setParameters([
            'socialNetwork' => $socialNetwork,
            'externalId' => $externalId,
        ]);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function findUserByUsername(string $username): User|null
    {
        $qb = $this->createQueryBuilder('u');

        $qb->select('u');

        $qb->andWhere('u.username = :username');

        $qb->setParameters([
            'username' => $username,
        ]);

        return $qb->getQuery()->getOneOrNullResult();
    }

    public function create(
        string $username,
        string|null $password = null,
        array $roles = [],
        SocialNetwork|null $socialNetwork = null,
        string|null $userExternalId = null,
    ): User {
        $user = new User(
            username: $username
        );
        $user->setPassword($password);
        $user->setRoles($roles);

        if ($socialNetwork instanceof SocialNetwork) {
            if (empty($userExternalId)) {
                throw new \LogicException(sprintf('You have specified a social network, "userExternalId" is required in this case'));
            }

            $userSocialNetwork = new UserSocialNetwork(
                socialNetwork: $socialNetwork,
                user: $user,
                userExternalId: $userExternalId
            );

            $user->getSocialNetworks()->add($userSocialNetwork);
            $this->getEntityManager()->persist($userSocialNetwork);
        }

        $this->getEntityManager()->persist($user);

        return $user;
    }

//    /**
//     * @return User[] Returns an array of User objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?User
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
