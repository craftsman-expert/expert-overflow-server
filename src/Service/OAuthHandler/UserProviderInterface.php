<?php

namespace App\Service\OAuthHandler;

use App\Service\OAuthHandler\Credential\CredentialInterface;

interface UserProviderInterface
{
    public function getUser(CredentialInterface $credential): mixed;
}
