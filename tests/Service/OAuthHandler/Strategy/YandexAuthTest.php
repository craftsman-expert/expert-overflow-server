<?php

namespace App\Tests\Service\OAuthHandler\Strategy;

use App\Entity\User;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\Strategy;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class YandexAuthTest extends KernelTestCase
{
    public function testAuthorize()
    {
        $userProvider = $this->createMock(OAuthUserProvider::class);
        $userProvider->expects(self::once())
            ->method('getUser')
            ->willReturn(new User(
                username: 'this is username'
            ));

        $api = $this->createMock(Strategy\Yandex\YandexApi::class);
        $api->expects(self::once())
            ->method('getAccessToken')
            ->willReturn('this is access token');

        $api->expects(self::once())
            ->method('getInfo')
            ->willReturn([
                'id' => 'user id in yandex',
            ]);

        $strategy = new Strategy\Yandex\YandexAuth(
            api: $api,
            userProvider: $userProvider
        );

        $user = $strategy->authorize([
            'code' => 'this is code from yandex',
        ]);

        self::assertInstanceOf(User::class, $user);
    }
}
