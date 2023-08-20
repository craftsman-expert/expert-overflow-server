<?php

namespace App\Service\OAuthHandler;

use App\Entity;
use App\Service\JWT;
use App\Service\OAuthHandler\Strategy\NullAuth\NullAuth;
use Doctrine\ORM\EntityManagerInterface;

class OAuthHandlerService
{
    private StrategyInterface $strategy;

    public function __construct(
        private JWT\JWTService $JWTService,
        private EntityManagerInterface $entityManager
    ) {
        $this->strategy = new NullAuth();
    }

    public function authorize(array $params): Entity\User
    {
        $user = $this->strategy->authorize($params);

        $uow = $this->entityManager->getUnitOfWork();
        $uow->computeChangeSets();

        // Если есть какие-то изменения, сохраняем
        if ($uow->isEntityScheduled($user)) {
            $this->entityManager->flush();
        }

        return $user;
    }

    public function setStrategy(StrategyInterface $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }
}
