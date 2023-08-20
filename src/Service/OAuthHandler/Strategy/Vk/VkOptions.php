<?php

namespace App\Service\OAuthHandler\Strategy\Vk;

class VkOptions
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $redirectUri,
    ) {
    }
}
