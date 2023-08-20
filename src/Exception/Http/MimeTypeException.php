<?php

namespace App\Exception\Http;

/**
 * Код состояния ответа HTTP 422 Invalid file type mime указывает,
 * не правильный тип файла (расширение файла).
 *
 * Class MimeTypeException
 */
class MimeTypeException extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }
}
