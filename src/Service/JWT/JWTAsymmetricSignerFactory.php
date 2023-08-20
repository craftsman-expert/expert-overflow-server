<?php

namespace App\Service\JWT;

use Lcobucci\JWT\Signer\Rsa;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Encoding\ChainedFormatter;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Signer\Key\InMemory;
use Lcobucci\JWT\Token\Builder;

class JWTAsymmetricSignerFactory
{
    public static function factory(
        Rsa\Sha512 $algorithm,
        string $signingKey,
        string $verificationKey,
    ): Configuration {
        $sk = is_file($signingKey) ? InMemory::file($signingKey) : InMemory::plainText($signingKey);

        $configuration = Configuration::forAsymmetricSigner(
            signer: $algorithm,
            signingKey: $sk,
            verificationKey: InMemory::plainText($verificationKey)
        );

        $configuration->setBuilderFactory(static function(): Builder {
            return new Builder(new JoseEncoder(), ChainedFormatter::default());
        });

        return $configuration;
    }
}
