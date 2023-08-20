<?php

namespace App\Service\OAuthHandler\Strategy\NullAuth;

use App\Entity;
use App\Service\OAuthHandler\StrategyInterface;

class NullAuth implements StrategyInterface
{
    public function authorize(array $params): Entity\User|null
    {
        return null;
    }
}
