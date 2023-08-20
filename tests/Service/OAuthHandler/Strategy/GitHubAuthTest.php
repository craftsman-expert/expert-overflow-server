<?php

namespace App\Tests\Service\OAuthHandler\Strategy;

use App\Entity\User;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\Strategy;
use PHPUnit\Framework\TestCase;

class GitHubAuthTest extends TestCase
{
    public function testAuthorize()
    {
        $userProvider = $this->createMock(OAuthUserProvider::class);
        $userProvider->expects(self::once())
            ->method('getUser')
            ->willReturn(new User(
                username: 'this is username'
            ));

        $api = $this->createMock(Strategy\GitHub\GitHubApi::class);
        $api->expects(self::once())
            ->method('getAccessToken')
            ->willReturn('this is access token');
        $api->expects(self::once())
            ->method('getUser')
            ->willReturn([
                'node_id' => 'random string',
            ]);

        $strategy = new Strategy\GitHub\GitHubAuth(
            api: $api,
            userProvider: $userProvider
        );

        $user = $strategy->authorize([
            'code' => 'this is code',
        ]);

        self::assertInstanceOf(User::class, $user);
    }
}
