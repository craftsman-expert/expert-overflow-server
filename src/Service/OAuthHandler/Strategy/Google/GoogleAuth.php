<?php

namespace App\Service\OAuthHandler\Strategy\Google;

use App\Entity;
use App\Service\OAuthHandler\StrategyInterface;

class GoogleAuth implements StrategyInterface
{
    public function __construct(private readonly GoogleOptions $options)
    {
    }

    public function authorize(array $params): Entity\User|null
    {
        return null;
    }
}
