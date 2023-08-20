<?php

namespace App\Service\OAuthHandler\Strategy\Vk;

use App\Entity;
use App\Service\OAuthHandler\Credential\OAuthCredential;
use App\Service\OAuthHandler\Exception\StrategyException;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\StrategyInterface;

class VkAuth implements StrategyInterface
{
    private const SOCIAL_NETWORK_KEY = 'vk';

    public function __construct(
        private readonly VkApi $api,
        private readonly OAuthUserProvider $userProvider
    ) {
    }

    public function authorize(array $params): Entity\User|null
    {
        if (isset($params['error_description'])) {
            throw new StrategyException($params['error_description']);
        }

        if (!isset($params['code'])) {
            throw new StrategyException('Invalid request parameters: argument "code" missing');
        }

        $accessToken = $this->api->getAccessToken($params['code']);

        $vkUser = $this->api->getOneUser(
            userId: $accessToken['user_id'],
            token: $accessToken['access_token']
        );

        $externalId = $vkUser['id']; // Идентификатор пользователя в социальной сети

        $user = $this->userProvider->getUser(
            credential: new OAuthCredential(
                externalId: $externalId,
                socialNetworkId: self::SOCIAL_NETWORK_KEY
            )
        );

        $user->setFirstName($vkUser['first_name']);
        $user->setSurname($vkUser['last_name']);
        $user->setAbout($vkUser['about']);
        $user->setAvatar($vkUser['photo_big']);

        return $user;
    }
}
