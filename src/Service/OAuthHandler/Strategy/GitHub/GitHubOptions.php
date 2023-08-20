<?php

namespace App\Service\OAuthHandler\Strategy\GitHub;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;

class GitHubOptions
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $redirectUri,
        public readonly string $login = '',
        public readonly string $scope = 'user',
        public readonly string $state = '',
        public readonly bool $allowSignup = false,
        public readonly LoggerInterface $logger = new NullLogger(),
    ) {
    }
}
