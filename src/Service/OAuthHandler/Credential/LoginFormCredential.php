<?php

namespace App\Service\OAuthHandler\Credential;

class LoginFormCredential implements CredentialInterface
{
    public function __construct(
        private readonly string $username
    ) {
    }

    public function getUsername(): string
    {
        return $this->username;
    }
}
