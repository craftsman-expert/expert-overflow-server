<?php

namespace App\Service\OAuthHandler\Strategy\GitHub;

use App\Service\OAuthHandler\Exception\ApiException;
use Symfony\Component\HttpClient\HttpClient;

class GitHubApi
{
    public function __construct(private readonly GitHubOptions $options)
    {
    }

    public function getAccessToken(string $code): string
    {
        $query = [
            'client_id' => $this->options->clientId,
            'client_secret' => $this->options->clientSecret,
            'code' => $code,
            'redirect_uri' => $this->options->redirectUri,
        ];

        if (!empty($this->option->redirectUri)) {
            $query['redirect_uri'] = $this->option->redirectUri;
        }

        $response = HttpClient::create([
            'base_uri' => 'https://github.com',
            'headers' => ['Accept' => 'application/json'],
        ])->request('GET', '/login/oauth/access_token', [
            'query' => $query,
        ]);

        if (200 != $response->getStatusCode()) {
            throw new ApiException(sprintf('Status code: %s, strategy: GitHubAuth', $response->getStatusCode()));
        }

        $data = json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);

        if (isset($data['error'])) {
            throw new ApiException(sprintf('GitHub responded with an error when getting a token: %s', $data['error']));
        }

        return $data['access_token'];
    }

    public function getUser(string $token): array
    {
        try {
            $response = HttpClient::create()->withOptions([
                'base_uri' => 'https://api.github.com',
                'headers' => [
                    'Authorization' => sprintf('Bearer %s', $token),
                ],
            ])->request('GET', '/user');

            return json_decode($response->getContent(), true, 512, JSON_THROW_ON_ERROR);
        } catch (\Exception $exception) {
            throw new ApiException(sprintf('Error getting profile. "%s"', $exception->getMessage()));
        }
    }
}
