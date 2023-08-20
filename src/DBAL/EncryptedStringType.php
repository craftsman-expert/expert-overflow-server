<?php

namespace App\DBAL;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * @author Igor Popryadukhin <igorpopryadukhin@gmail.com>
 * encryptedString
 */
class EncryptedStringType extends Type
{
    public const TYPE_NAME = 'encrypted_string';

    public const CIPHER_ALGO = 'aes-256-cbc';
    private string $key = '12345'; // TODO: УКАЖИ НАДЁЖНЫЙ КЛЮЧ
    private bool $isChecked = false;

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return 'TEXT';
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?string
    {
        if (!is_string($value) || empty($value)) {
            return null;
        }

        return $this->decrypt($value, $this->key);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): mixed
    {
        if (!is_string($value) || empty($value)) {
            return parent::convertToDatabaseValue($value, $platform);
        }

        return $this->encrypt($value, $this->key);
    }

    public function getName(): string
    {
        return self::TYPE_NAME;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }

    private function encrypt(string $text, string $key)
    {
        $this->checkAlgorithmSupported();

        // Generate an initialization vector
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER_ALGO));

        // Encrypt the data using AES 256 encryption in CBC mode using our encryption key and initialization vector.
        $encrypted = openssl_encrypt($text, self::CIPHER_ALGO, $key, 0, $iv);

        // The $iv is just as important as the key for decrypting, so save it with our encrypted data using a unique separator (::)
        return base64_encode($encrypted . '::' . $iv);
    }

    private function decrypt(string $text, string $key): string|false
    {
        $this->checkAlgorithmSupported();

        // Remove the base64 encoding from our key
        $encryption_key = base64_decode($key);

        // To decrypt, split the encrypted data from our IV - our unique separator used was "::"
        [$encrypted_data, $iv] = explode('::', base64_decode($text), 2);

        return openssl_decrypt($encrypted_data, self::CIPHER_ALGO, $encryption_key, 0, $iv);
    }

    private function checkAlgorithmSupported(): void
    {
        if ($this->isChecked) {
            return;
        }

        $cipherMethods = openssl_get_cipher_methods();

        if (!in_array(self::CIPHER_ALGO, $cipherMethods)) {
            throw new \UnexpectedValueException(sprintf('%s" is not supported, available only [%s]', self::CIPHER_ALGO, implode(',', $cipherMethods)));
        }

        $this->isChecked = true;
    }
}
