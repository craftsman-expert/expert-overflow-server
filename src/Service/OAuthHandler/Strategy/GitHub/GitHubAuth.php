<?php

namespace App\Service\OAuthHandler\Strategy\GitHub;

use App\Entity;
use App\Service\OAuthHandler\Credential\OAuthCredential;
use App\Service\OAuthHandler\Exception\StrategyException;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\StrategyInterface;
use App\Service\OAuthHandler\Trait\UserUpdate;

class GitHubAuth implements StrategyInterface
{
    use UserUpdate;

    private const SOCIAL_NETWORK_KEY = 'github';

    public function __construct(
        private readonly GitHubApi $api,
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

        $gitHubProfileData = $this->api->getUser($accessToken);

        $externalId = $gitHubProfileData['node_id'];

        $user = $this->userProvider->getUser(
            credential: new OAuthCredential(
                externalId: $externalId,
                socialNetworkId: self::SOCIAL_NETWORK_KEY
            )
        );

        return $user;
    }
}
