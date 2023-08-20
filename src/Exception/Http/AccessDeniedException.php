<?php

namespace App\Exception\Http;

/**
 * Код ответа на статус ошибки "HTTP 403 Forbidden" указывает, что сервер понял запрос, но отказывается его авторизовать.
 *
 * Этот статус похож на 401, но в этом случае повторная аутентификация не будет иметь никакого значения.
 * Доступ запрещён и привязан к логике приложения (например, у пользователя не хватает прав доступа к запрашиваемому ресурсу).
 */
class AccessDeniedException extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 403, $previous);
    }
}
