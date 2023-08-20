<?php

namespace App\Tests\Service\OAuthHandler\Strategy;

use App\Entity\User;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\Strategy;
use PHPUnit\Framework\TestCase;

class VkAuthTest extends TestCase
{
    public function testAuthorize()
    {
        $userProvider = $this->createMock(OAuthUserProvider::class);
        $userProvider->expects(self::once())
            ->method('getUser')
            ->willReturn(new User(
                username: 'this is username'
            ));

        $api = $this->createMock(Strategy\Vk\VkApi::class);
        $api->expects(self::once())
            ->method('getAccessToken')
            ->willReturn([
                'access_token' => 'vk1.a.onWh8426H2VN3Yv...',
                'expires_in' => 1,
                'user_id' => 1,
            ]);

        $api->expects(self::once())
            ->method('getOneUser')
            ->willReturn([
                'id' => 'integer',
                'bdate' => 'string',
                'photo_big' => 'string',
                'sex' => 'integer',
                'screen_name' => 'string',
                'first_name' => 'string',
                'last_name' => 'string',
                'about' => 'string',
            ]);

        $strategy = new Strategy\Vk\VkAuth(
            api: $api,
            userProvider: $userProvider
        );

        $user = $strategy->authorize([
            'code' => 'this is code from vk.com',
        ]);

        self::assertInstanceOf(User::class, $user);
    }
}
