<?php

namespace App\Service\Avatar;

use App\Service\ImageConverter;
use League\Flysystem\FilesystemOperator;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AvatarService
{
    private HttpClientInterface|null $httpClient = null;

    public function __construct(
        private readonly FilesystemOperator $avatarsStorage,
        private readonly ImageConverter $imageConverter,
    ) {
    }

    public function getAvatar(string|UuidInterface $uuid): string
    {
        $domain = 'http://expert-overflow.loc'; // TODO: From configuration
        $bucket = 'expert-overflow'; // TODO: From configuration

        return sprintf('%s/%s/avatars/%s.jpg', $domain, $bucket, $this->generatePath($uuid));
    }

    public function download(string $url, string|UuidInterface $uuid): void
    {
        try {
            $response = $this
                ->getHttpClient()
                ->request('GET', $url);

            if (200 !== $response->getStatusCode()) {
                throw new \Exception(sprintf('Произошла ошибка при скачивании файла, статус: %s', $response->getStatusCode()));
            }

            $contentType = $response->getHeaders()['content-type'][0];
            $avatarFileName = sprintf('%s.jpg', $this->generatePath($uuid));

            switch ($contentType) {
                case 'image/jpg':
                case 'image/jpeg':
                    $this->avatarsStorage->write($avatarFileName, $response->getContent());
                    break;

                case 'image/png':
                    // png to jpg conversion
                    $tempFilePng = tempnam(sys_get_temp_dir(), 'png');
                    $tempFileJpg = tempnam(sys_get_temp_dir(), 'jpg');
                    file_put_contents($tempFilePng, $response->getContent());
                    $this->imageConverter->pngToJpg(
                        in: $tempFilePng,
                        out: $tempFileJpg
                    );
                    // png to jpg conversion

                    $this->avatarsStorage->write($avatarFileName, file_get_contents($tempFileJpg));
                    break;
            }
        } catch (TransportExceptionInterface $e) {
        } catch (\Exception $exception) {
        }
    }

    private function getHttpClient(): HttpClientInterface
    {
        if (is_null($this->httpClient)) {
            $this->httpClient = HttpClient::create();
        }

        return $this->httpClient;
    }

    /**
     * @param string|UuidInterface $uuid Input c1b2d9b6-4bfa-439f-a52f-cf927637a296
     *
     * @return string Output c1/b2/c1b2d9b6-4bfa-439f-a52f-cf927637a296
     */
    private function generatePath(string|UuidInterface $uuid): string
    {
        if ($uuid instanceof UuidInterface) {
            $innerUuid = $uuid->toString();
        } else {
            $innerUuid = $uuid;
        }

        $chunks = str_split($innerUuid, 2);

        return implode('/', [array_shift($chunks), array_shift($chunks), $innerUuid]);
    }
}
