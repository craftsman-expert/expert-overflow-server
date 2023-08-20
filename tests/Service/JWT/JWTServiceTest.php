<?php

namespace App\Tests\Service\JWT;

use App\Service\JWT\JWTService;
use Lcobucci\JWT\Signer\Rsa\Sha512;
use Lcobucci\JWT\UnencryptedToken;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class JWTServiceTest extends WebTestCase
{
    private mixed $tempPem = null;
    private ?string $signingKey = null;

    protected function setUp(): void
    {
        $keySize = 2048;
        $key = openssl_pkey_new([
            'private_key_bits' => $keySize,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ]);

        openssl_pkey_export($key, $privateKey);

        $this->tempPem = tmpfile();
        fwrite($this->tempPem, $privateKey);
        fseek($this->tempPem, 0);

        $meta_data = stream_get_meta_data($this->tempPem);

        $this->signingKey = $meta_data['uri'];
    }

    public function testParse(): void
    {
        $jwt = new JWTService(
            new Sha512(),
            signingKey: $this->signingKey,
            verificationKey: 'verificationKey',
            issued: 'expert-overflow.loc',
            expires: '+1 hour'
        );

        $token = $jwt->makeJWT(
            permitted: [
                'expert-overflow.loc',
            ],
            uuid: '00000000-0000-0000-0000-000000000000'
        );

        $this->assertInstanceOf(UnencryptedToken::class, $token);
        $this->assertTrue($jwt->verify($token->toString()));
        $this->assertTrue($jwt->parse($token->toString())->claims()->has('uuid'));
    }

    protected function tearDown(): void
    {
        fclose($this->tempPem);
    }
}
