<?php

namespace App\Service\OAuthHandler\Strategy\Google;

class GoogleOptions
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $redirectUri,
    ) {
    }
}
