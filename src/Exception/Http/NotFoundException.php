<?php

namespace App\Exception\Http;

/**
 * Код ответа на ошибку HTTP 404 Not Found указывает, что сервер не может найти запрошенный ресурс.
 * Ссылки, ведущие к коду 404, часто называются сломанными или мёртвыми связями и приводят к ссылочной гнили.
 *
 * Код статуса 404 не уточняет, отсутствует ли запрашиваемый ресурс временно или постоянно.
 * Но если серверу известно, что указанный ресурс удалён навсегда, то вместо статуса 404 следует использовать 410 (Gone) .
 */
class NotFoundException extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }
}
