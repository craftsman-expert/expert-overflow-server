<?php

namespace App\Service\JWT;

use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\UnencryptedToken;
use Lcobucci\JWT\Validation\Constraint\IssuedBy;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class JWTService
{
    private array $cache = [];
    private Configuration $configuration;

    public function __construct(
        Rsa\Sha512 $algorithm,

        #[Autowire('%app.jwt.signingKey%')]
        string $signingKey,

        #[Autowire('%app.jwt.verificationKey%')]
        string $verificationKey,

        #[Autowire('%app.jwt.issued%')]
        private readonly string $issued,

        #[Autowire('%app.jwt.expires%')]
        private readonly string $expires,
    ) {
        $this->configuration = JWTAsymmetricSignerFactory::factory(
            algorithm: $algorithm,
            signingKey: $signingKey,
            verificationKey: $verificationKey
        );
    }

    public function makeJWT(
        array $permitted,
        string $uuid,
    ): UnencryptedToken {
        $now = new \DateTimeImmutable('now', new \DateTimeZone('+00:00'));

        $builder = $this->configuration->builder()
            ->issuedBy($this->issued)
            ->permittedFor(...$permitted)
            ->issuedAt($now)
            ->canOnlyBeUsedAfter($now->modify('+1 minute'))
            ->withClaim('uuid', $uuid)
            ->expiresAt($now->modify($this->expires));

        // Builds a new token
        return $builder->getToken($this->configuration->signer(), $this->configuration->signingKey());
    }

    public function parse(string $jwt): UnencryptedToken
    {
        $jwtMD5 = md5($jwt);

        if (!isset($this->cache[$jwtMD5])) {
            $this->cache[$jwtMD5] = $this->configuration->parser()->parse($jwt);
        }

        return $this->cache[$jwtMD5];
    }

    public function verify(string $jwt): bool
    {
        $validator = $this->configuration->validator();
        $parser = $this->configuration->parser();

        try {
            $token = $parser->parse($jwt);

            return $validator->validate(
                $token,
                new IssuedBy($this->issued)
            );
        } catch (\Throwable $throwable) {
            return false;
        }
    }
}
