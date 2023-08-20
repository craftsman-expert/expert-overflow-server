<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    private EntityManagerInterface $entityManager;

    /**
     * UserProvider constructor.
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function loadUserByUsername(string $username)
    {
        return $this->entityManager->getRepository(User::class)->findOneBy(['login' => $username]);
    }

    public function loadUserById(Uuid $id): mixed
    {
        return $this->entityManager->getRepository(User::class)->find($id);
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new \Exception('TODO: fill in loadUserByUsername() inside ' . __FILE__);
    }

    public function supportsClass(string $class): bool
    {
        return User::class === $class;
    }

    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        return $this->entityManager->getRepository(User::class)->find($identifier);
    }
}
