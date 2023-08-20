<?php

namespace App\Exception\Http;

/**
 * Код состояния ответа "HTTP 400 Bad Request" указывает, что сервер не смог понять запрос из-за
 * недействительного синтаксиса. Клиент не должен повторять этот запрос без изменений.
 */
class BadRequestException extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 400, $previous);
    }
}
