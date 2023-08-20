<?php

namespace App\Service\OAuthHandler\Strategy\Yandex;

use App\Entity;
use App\Service\OAuthHandler\Credential\OAuthCredential;
use App\Service\OAuthHandler\Exception\StrategyException;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\StrategyInterface;

class YandexAuth implements StrategyInterface
{
    private const SOCIAL_NETWORK_KEY = 'yandex';

    public function __construct(
        private readonly YandexApi $api,
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

        $yandexProfileData = $this->api->getInfo($accessToken);

        $externalId = $yandexProfileData['id'];

        return $this->userProvider->getUser(
            credential: new OAuthCredential(
                externalId: $externalId,
                socialNetworkId: self::SOCIAL_NETWORK_KEY
            )
        );
    }
}
