<?php

namespace Omadonex\LaravelTools\Support\Classes\Exceptions;

use Exception;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Translation\Translator;
use Throwable;

class OmxUserException extends Exception
{
    public const ERR_CODE_1000 = 1000;

    /** Нельзя повторно назначить одну и ту же роль */
    public const ERR_CODE_1001 = 1001;
    /** Нельзя удалить самого себя */
    public const ERR_CODE_1002 = 1002;
    /** Указан неверный пароль */
    public const ERR_CODE_1003 = 1003;
    /** Нельзя удалять зарезервированные роли */
    public const ERR_CODE_1004 = 1004;

    public static function throw(int $code, ?Throwable $previous = null): void
    {
        throw new self(self::getMessageError($code), $code, $previous);
    }

    public static function getMessageError(int $code, array $boundVars = []): array|string|null|Translator|Application
    {
        $langFile = $code <= 1099 ? 'omx-support::exceptions' : 'exceptions';

        return __("{$langFile}.{$code}", $boundVars);
    }
}
