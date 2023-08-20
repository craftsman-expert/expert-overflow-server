<?php

namespace App\Service\OAuthHandler;

use App\Entity;

interface StrategyInterface
{
    public function authorize(array $params): Entity\User|null;
}
