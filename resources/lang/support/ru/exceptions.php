<?php

use Omadonex\LaravelTools\Support\Classes\Exceptions\OmxUserException;

return [
    OmxUserException::ERR_CODE_1001 => 'Нельзя повторно назначить одну и ту же роль!',
    OmxUserException::ERR_CODE_1002 => 'Нельзя удалить самого себя!',
    OmxUserException::ERR_CODE_1003 => 'Указан неверный пароль!',
    OmxUserException::ERR_CODE_1004 => 'Нельзя удалять зарезервированные роли',
];