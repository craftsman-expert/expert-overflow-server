<?php

namespace App\Exception\Http;

/**
 * HTTP 409 Conflict код состояния ответа указывает на конфликт запроса с текущим состоянием сервера.
 *
 * Конфликты чаще всего возникают в ответ на PUT запрос. Например, вы можете получить ответ 409
 * при загрузке файла, который старше, чем тот, который уже существует на сервере, что приводит к
 * конфликту управления версиями.
 */
class ConflictException extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 409, $previous);
    }
}
