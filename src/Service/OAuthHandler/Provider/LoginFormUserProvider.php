<?php

namespace App\Service\OAuthHandler\Provider;

use App\Entity;
use App\Service\OAuthHandler\Credential\CredentialInterface;
use App\Service\OAuthHandler\Credential\LoginFormCredential;
use App\Service\OAuthHandler\UserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

class LoginFormUserProvider implements UserProviderInterface
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function getUser(CredentialInterface $credential): Entity\User
    {
        if (!$credential instanceof LoginFormCredential) {
            throw new \Exception('Credential not supported!');
        }

        return $this
            ->entityManager
            ->getRepository(Entity\User::class)
            ->findOneBy([
                'username' => $credential->getUsername(),
            ]);
    }
}
