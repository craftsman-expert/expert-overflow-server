<?php

namespace App\Service\OAuthHandler\Strategy\Vk;

use App\Service\OAuthHandler\Exception\ApiException;
use App\Service\OAuthHandler\Exception\OAuthHandlerException;
use JetBrains\PhpStorm\ArrayShape;
use Symfony\Component\HttpClient\HttpClient;

class VkApi
{
    public function __construct(private readonly VkOptions $options)
    {
    }

    #[ArrayShape([
        'access_token' => 'string',
        'expires_in' => 'integer',
        'user_id' => 'integer',
    ])]
    public function getAccessToken(string $code): array
    {
        $query = [
            'code' => $code,
            'client_id' => $this->options->clientId,
            'client_secret' => $this->options->clientSecret,
            'redirect_uri' => $this->options->redirectUri,
        ];

        $response = HttpClient::create([
            'base_uri' => 'https://oauth.vk.com',
            'headers' => [
                'Accept' => 'application/json',
            ],
        ])->request('GET', '/access_token', [
            'query' => $query,
        ]);

        if (200 != $response->getStatusCode()) {
            throw new OAuthHandlerException(sprintf('Status code: %s', $response->getStatusCode()));
        }

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['error'])) {
            throw new ApiException(sprintf('Vk responded with an error when getting a token: %s', $data['error']));
        }

        return $data;
    }

    #[ArrayShape([
        'id' => 'integer',
        'bdate' => 'string',
        'photo_big' => 'string',
        'sex' => 'integer',
        'screen_name' => 'string',
        'first_name' => 'string',
        'last_name' => 'string',
        'about' => 'string',
    ])]
    public function getOneUser(int $userId, string $token): array
    {
        try {
            $response = HttpClient::create()->withOptions([
                'base_uri' => 'https://api.vk.com',
                'headers' => [
                    'Accept' => 'application/json',
                    'Authorization' => sprintf('Bearer %s', $token),
                ],
            ])->request('POST', '/method/users.get', [
                'query' => [
                    'user_ids' => $userId,
                    'fields' => 'uid,first_name,last_name,screen_name,sex,bdate,photo_big,about',
                    'v' => '5.131',
                ],
            ]);

            $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

            return $data['response'][0];
        } catch (\Exception $exception) {
            throw new ApiException(sprintf('Error getting profile. %s', $exception->getMessage()));
        }
    }
}
