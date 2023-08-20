<?php

namespace App\Exception\Http;

/**
 * Код ответа на статус ошибки HTTP 401 Unauthorized клиента указывает, что запрос не был применён,
 * поскольку ему не хватает действительных учётных данных для целевого ресурса.
 *
 * Этот статус отправляется с WWW-Authenticate (en-US), который содержит информацию о правильности авторизации.
 *
 * Этот статус похож на 403, но в этом случае возможна аутентификация.
 */
class UnauthorizedException extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 401, $previous);
    }
}
