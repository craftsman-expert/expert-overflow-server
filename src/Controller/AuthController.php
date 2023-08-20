<?php

namespace App\Controller;

use App\Entity;
use App\Service\JWT\JWTService;
use App\Service\OAuthHandler\OAuthHandlerService;
use App\Service\OAuthHandler\OAuthHandlerStrategyFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/auth')]
class AuthController extends AbstractController
{
    #[Route(
        path: '/{strategy}-authorize',
        name: 'authorize',
        requirements: [
            'strategy' => 'github|vk|yandex|google|classic',
        ]
    )]
    public function authorize(
        string $strategy,
        Request $request,
        OAuthHandlerService $authHandlerService,
        OAuthHandlerStrategyFactory $strategyFactory,
        JWTService $jwtService,
    ): Response {
        $authHandlerService->setStrategy($strategyFactory->getStrategy($strategy));

        /** @var Entity\User|mixed $user */
        $user = $authHandlerService->authorize(
            params: array_merge($request->request->all(), $request->query->all())
        );

        // TODO: Use other http exception!!!
        if (!$user instanceof Entity\User) {
            throw new \Exception();
        }

        $jwt = $jwtService->makeJWT(
            permitted: [
                'expert-overflow.loc',
            ],
            uuid: $user->getUuid()->toString(),
        );

        $response = new Response('', 204);
        $response->headers->setCookie(Cookie::create('jwt')
            ->withValue($jwt->toString())
            ->withHttpOnly()
            ->withSecure('prod' === $this->getParameter('environment'))
            ->withExpires((new \DateTimeImmutable('now', new \DateTimeZone('+00:00')))->modify($this->getParameter('app.jwt.expires'))));

        return $response;
    }

    #[Route('/token', name: 'oauth_token')]
    public function token(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProfileController.php',
        ]);
    }
}
