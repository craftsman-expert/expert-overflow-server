<?php

namespace App\EventSubscriber;

use App\Service\JWT;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ResponseSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private JWT\JWTService $jwtService,

        #[Autowire('%app.jwt.expires%')]
        private readonly string $jwtExpires,

        #[Autowire('%app.jwt.reissue%')]
        private readonly ?string $jwtReissue,
    ) {
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'onKernelResponse',
        ];
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (!$event->isMainRequest()) {
            // don't do anything if it's not the master request
            return;
        }

        $request = $event->getRequest();
        $response = $event->getResponse();

        if ($request->cookies->has('jwt')) {
            // 1. Извлекаю объект токена
            try {
                $jwt = $this->jwtService->parse($request->cookies->get('jwt'));

                $now = new \DateTimeImmutable('now', new \DateTimeZone('+00:00'));

                // 2. Проверяю сколько осталось жить
                if ($jwt->isExpired($now->modify($this->jwtReissue))) {
                    // 3. Подписываю новый токен
                    $response->headers->setCookie(Cookie::create('jwt')
                        ->withValue(
                            value: $this->jwtService->makeJWT(
                                permitted: [
                                    'expert-overflow.loc',
                                    'x-overflow.loc',
                                ],
                                uuid: $jwt->claims()->get('uuid'),
                            )->toString()
                        )
                        ->withHttpOnly()
                        ->withSecure(false)
                        ->withExpires($now->modify($this->jwtExpires)));
                }
            } catch (\Exception $exception) {
                $response->headers->clearCookie('jwt');
            }
        }
    }
}
