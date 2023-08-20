<?php

namespace App\Service\OAuthHandler\Credential;

class OAuthCredential implements CredentialInterface
{
    public function __construct(
        private string $externalId,
        private string $socialNetworkId
    ) {
    }

    public function getExternalId(): string
    {
        return $this->externalId;
    }

    public function getSocialNetworkId(): string
    {
        return $this->socialNetworkId;
    }
}
