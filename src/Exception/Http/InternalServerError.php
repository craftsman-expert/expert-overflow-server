<?php

namespace App\Exception\Http;

/**
 * Код ответа сервера 500 Internal Server Error  указывает на то,
 * что сервер столкнулся с неожиданной ошибкой, которая помешала ему выполнить запрос.
 *
 * Этот код является обобщённым ответом на перехват всех исключений,
 * которые не были обработаны должным образом. Обычно это означает,
 * что сервер не смог найти более подходящего кода ответа.
 *
 * Зачастую администраторы сервера регистрируют (логируют) сообщения об ошибках,
 * подобных коду состояния 500 (включая дополнительную информацию о запросе),
 * чтобы предотвратить повторение ошибки в будущем.
 *
 * Class UnprocessableEntity
 */
class InternalServerError extends \Exception implements HTTPStatusExceptionInterface
{
    public function __construct(string $message, $previous = null)
    {
        parent::__construct($message, 500, $previous);
    }
}
