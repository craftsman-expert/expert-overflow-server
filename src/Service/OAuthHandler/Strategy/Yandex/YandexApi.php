<?php

namespace App\Service\OAuthHandler\Strategy\Yandex;

use App\Service\OAuthHandler\Exception\ApiException;
use App\Service\OAuthHandler\Exception\OAuthHandlerException;
use Symfony\Component\HttpClient\HttpClient;

class YandexApi
{
    public function __construct(private readonly YandexOptions $options)
    {
    }

    public function getAccessToken(string $code): string
    {
        $body = http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'client_id' => $this->options->clientId,
            'client_secret' => $this->options->clientSecret,
        ]);

        $response = HttpClient::create([
            'base_uri' => 'https://oauth.yandex.ru',
            'headers' => [
                'Content-Length' => strlen($body),
                'Content-Type' => 'application/x-www-form-urlencoded',
                'Authorization' => sprintf('Basic %s', base64_encode(sprintf('%s:%s', $this->options->clientId, $this->options->clientSecret))),
            ],
        ])->request('POST', '/token', [
            'body' => $body,
        ]);

        if (200 != $response->getStatusCode()) {
            throw new OAuthHandlerException(sprintf('Status code: %s', $response->getStatusCode()));
        }

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['error'])) {
            throw new ApiException(sprintf('Yandex responded with an error when getting a token: %s', $data['error']));
        }

        return $data['access_token'];
    }

    public function getInfo(string $token): array
    {
        try {
            $response = HttpClient::create()->withOptions([
                'base_uri' => 'https://login.yandex.ru',
                'headers' => [
                    'Authorization' => sprintf('OAuth %s', $token),
                ],
            ])->request('GET', '/info', [
                'query' => [
                    'format' => 'json',
                    'jwt_secret' => $this->options->clientSecret,
                ],
            ]);

            return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            throw new ApiException(sprintf('Error getting profile, strategy: YandexAuth, Message: "%s"', $exception->getMessage()));
        }
    }
}
