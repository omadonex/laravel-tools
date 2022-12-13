<?php

use Omadonex\LaravelSupport\Classes\ConstCustom;

return [
    'OmxUnexpectedException' => [
        'message' => 'Непредвиденная ошибка: (код: `:code`; сообщение: `:message`)',
    ],
    'OmxShellException' => [
        'message' => 'Ошибка выполнения консольного скрипта (результат: `:result`)'
    ],
    'OmxBadParameterEnabledException' => [
        'message' => 'Параметр `' . ConstCustom::REQUEST_PARAM_ENABLED . '` может принимать одно из следующих значений: false | true',
    ],
    'OmxBadParameterPaginateException' => [
        'message' => 'Параметр `' . ConstCustom::REQUEST_PARAM_PAGINATE . '` может принимать одно из следующих значений: "false | true | integer"',
    ],
    'OmxBadParameterRelationsException' => [
        'message' => 'Параметр `' . ConstCustom::REQUEST_PARAM_RELATIONS . '` может принимать одно из следующих значений: "false | true | array (:relations)"',
    ],
    'OmxBadParameterTrashedException' => [
        'message' => 'Параметр `' . ConstCustom::REQUEST_PARAM_TRASHED . '` может принимать одно из следующих значений: "with | only"',
    ],
    'OmxClassNotUsesTraitException' => [
        'message' => 'Класс `:class` не использует trait `:trait`',
    ],
    'OmxMethodNotFoundInClassException' => [
        'message' => 'Метод `:method` не найден в классе `:class`',
    ],
    'OmxMethodNotImplementedInClassException' => [
        'message' => 'Интерфейсный метод `:method` не имеет реализации в классе `:class`',
    ],
    'OmxModelNotSearchedException' => [
        'message' => 'Запись в таблице `:table` с заданными условиями не найдена (модель `:class`)',
    ],
    'OmxModelNotSmartFoundException' => [
        'message' => 'Запись в таблице `:table` с `:field`=:value не найдена (модель `:class`)',
    ],
    'OmxModelProtectedException' => [
        'message' => 'Запись защищена и не может быть изменена (модель `:class`)',
    ],
];