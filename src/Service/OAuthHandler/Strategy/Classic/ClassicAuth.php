<?php

namespace App\Service\OAuthHandler\Strategy\Classic;

use App\Entity;
use App\Service\OAuthHandler\Credential\LoginFormCredential;
use App\Service\OAuthHandler\Exception\StrategyException;
use App\Service\OAuthHandler\Provider\LoginFormUserProvider;
use App\Service\OAuthHandler\StrategyInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClassicAuth implements StrategyInterface
{
    public function __construct(
        private readonly LoginFormUserProvider $userProvider,
        private readonly UserPasswordHasherInterface $hasher,
    ) {
    }

    public function authorize(array $params): Entity\User|null
    {
        if (!isset($params['login'], $params['password'])) {
            throw new StrategyException('Invalid parameters! Requires login and password');
        }

        $user = $this->userProvider->getUser(new LoginFormCredential(
            username: $params['login']
        ));

        if (!$this->hasher->isPasswordValid($user, $params['password'])) {
            throw new StrategyException('Incorrect password!');
        }

        return $user;
    }
}
