<?php

namespace App\Security\Authenticator;

use App\Entity\User;
use App\Service\JWT\JWTService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

final class Authenticator extends AbstractAuthenticator
{
    /**
     * Authenticator constructor.
     */
    public function __construct(
        private JWTService $jwtService,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function supports(Request $request): ?bool
    {
        $token = $this->getJWToken($request);

        if (is_null($token)) {
            return false; // Not supported
        }

        if ($this->jwtService->verify($token)) {
            return null; // Lazy authentication
        }

        return false; // Not supported
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        throw $exception;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        return null;
    }

    public function authenticate(Request $request): Passport
    {
        $jwt = $this->jwtService->parse($this->getJWToken($request));

        return new SelfValidatingPassport(new UserBadge($jwt->claims()->get('uuid'), function(string $userIdentifier) {
            $user = $this->entityManager->getRepository(User::class)->findOneBy([
                'uuid' => $userIdentifier,
            ]);

            if (is_null($user)) {
                throw new UserNotFoundException();
            }

            return $user;
        }));
    }

    private function getJWToken(Request $request): ?string
    {
        $token = $request->cookies->get('jwt', false);

        if ($token) {
            return $token;
        }

        $authorizationHeader = $request->headers->get('authorization');

        if (preg_match('/Bearer\s+(.*?)$/m', $authorizationHeader, $match)) {
            return $match[1];
        }

        return null;
    }
}
