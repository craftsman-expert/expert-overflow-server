<?php

namespace App\Service\OAuthHandler\Strategy\Yandex;

class YandexOptions
{
    public function __construct(
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $redirectUri,
    ) {
    }
}
