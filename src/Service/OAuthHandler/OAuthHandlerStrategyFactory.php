<?php

namespace App\Service\OAuthHandler;

use App\Service\OAuthHandler\Provider\LoginFormUserProvider;
use App\Service\OAuthHandler\Provider\OAuthUserProvider;
use App\Service\OAuthHandler\Strategy\Classic\ClassicAuth;
use App\Service\OAuthHandler\Strategy\GitHub\GitHubApi;
use App\Service\OAuthHandler\Strategy\GitHub\GitHubAuth;
use App\Service\OAuthHandler\Strategy\GitHub\GitHubOptions;
use App\Service\OAuthHandler\Strategy\Google\GoogleAuth;
use App\Service\OAuthHandler\Strategy\Google\GoogleOptions;
use App\Service\OAuthHandler\Strategy\Vk\VkApi;
use App\Service\OAuthHandler\Strategy\Vk\VkAuth;
use App\Service\OAuthHandler\Strategy\Vk\VkOptions;
use App\Service\OAuthHandler\Strategy\Yandex\YandexApi;
use App\Service\OAuthHandler\Strategy\Yandex\YandexAuth;
use App\Service\OAuthHandler\Strategy\Yandex\YandexOptions;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class OAuthHandlerStrategyFactory
{
    public function __construct(
        private readonly OAuthUserProvider $oAuthUserProvider,
        private readonly LoginFormUserProvider $loginFormUserProvider,
        private readonly UserPasswordHasherInterface $userPasswordHasher,
    ) {
    }

    public function getStrategy(string $id): StrategyInterface
    {
        return match ($id) {
            'github' => new GitHubAuth(
                api: new GitHubApi(
                    options: new GitHubOptions(
                        clientId: '6094e6f6985c30423720',
                        clientSecret: '95cfb8993e384265f6bbef953ce290b1ee6b2b19',
                        redirectUri: 'https://9f1a-5-187-75-32.ngrok-free.app/auth/github-authorize',
                        scope: 'user',
                        state: 'random',
                    )
                ),
                userProvider: $this->oAuthUserProvider
            ),

            'vk' => new VkAuth(
                api: new VkApi(
                    options: new VkOptions(
                        clientId: '51721458',
                        clientSecret: 'UDl7zU2CRheJSNtuJcwz',
                        redirectUri: 'https://9f1a-5-187-75-32.ngrok-free.app/auth/vk-authorize',
                    )
                ),
                userProvider: $this->oAuthUserProvider
            ),

            'yandex' => new YandexAuth(
                api: new YandexApi(
                    options: new YandexOptions(
                        clientId: 'cfb836d9dae14abe96545d52260122c7',
                        clientSecret: 'bcab1964bfa74247b7784d4339c69e03',
                        redirectUri: 'https://9f1a-5-187-75-32.ngrok-free.app/auth/yandex-authorize',
                    )
                ),
                userProvider: $this->oAuthUserProvider
            ),

            'google' => new GoogleAuth(new GoogleOptions(
                clientId: '6094e6f6985c30423720',
                clientSecret: '95cfb8993e384265f6bbef953ce290b1ee6b2b19',
                redirectUri: '95cfb8993e384265f6bbef953ce290b1ee6b2b19',
            )),

            'classic' => new ClassicAuth(
                userProvider: $this->loginFormUserProvider,
                hasher: $this->userPasswordHasher
            ),

            default => throw new \Exception(sprintf('Unknown %s strategy', $id)),
        };
    }
}
