<?php

namespace App\Service\OAuthHandler\Provider;

use App\Entity;
use App\Service\OAuthHandler\Credential\CredentialInterface;
use App\Service\OAuthHandler\Credential\OAuthCredential;
use App\Service\OAuthHandler\Exception\ProviderException;
use App\Service\OAuthHandler\UserProviderInterface;
use Doctrine\ORM\EntityManagerInterface;

class OAuthUserProvider implements UserProviderInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function getUser(CredentialInterface $credential): Entity\User
    {
        if (!$credential instanceof OAuthCredential) {
            throw new \Exception('Credential not supported!');
        }

        $socialNetwork = $this->getSocialNetwork(
            socialNetworkId: $credential->getSocialNetworkId()
        );

        if (is_null($socialNetwork)) {
            throw new ProviderException(sprintf('"%s" social network is not available', $credential->getSocialNetworkId()));
        }

        $user = $this
            ->entityManager
            ->getRepository(Entity\User::class)
            ->findUserBySocialNetwork(
                socialNetwork: $credential->getSocialNetworkId(),
                externalId: $credential->getExternalId()
            );

        if (!$user instanceof Entity\User) {
            $user = $this->createUser(
                socialNetwork: $socialNetwork,
                externalId: $credential->getExternalId(),
            );
        }

        return $user;
    }

    private function createUser(
        Entity\SocialNetwork $socialNetwork,
        string $externalId,
    ): Entity\User {
        return $this
            ->entityManager
            ->getRepository(Entity\User::class)
            ->create(
                username: uniqid('user-', true),
                socialNetwork: $socialNetwork,
                userExternalId: $externalId,
            );
    }

    private function getSocialNetwork(string $socialNetworkId): Entity\SocialNetwork|null
    {
        return $this
            ->entityManager
            ->getRepository(Entity\SocialNetwork::class)
            ->findOneBy([
                'key' => $socialNetworkId,
            ]);
    }
}
