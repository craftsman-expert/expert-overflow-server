<?php

namespace App\Service\OAuthHandler;

use Doctrine\ORM\EntityManagerInterface;

class OAuthContext
{
    public function __construct(
        public readonly array $params,
        public readonly EntityManagerInterface $entityManager,
    ) {
    }
}
