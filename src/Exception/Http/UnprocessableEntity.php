<?php

namespace App\Exception\Http;

/**
 * Код состояния ответа HTTP 422 Unprocessable Entity указывает,
 * что сервер понимает тип содержимого в теле запроса и синтаксис
 * запроса является правильным, но серверу не удалось обработать
 * инструкции содержимого.
 */
class UnprocessableEntity extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 422, $previous);
    }
}
