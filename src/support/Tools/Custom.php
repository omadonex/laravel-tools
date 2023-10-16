<?php

namespace Omadonex\LaravelTools\Support\Tools;


class Custom
{
    public static function yesNoList(bool $default = true, string $defaultCaption = 'Не выбрано'): array
    {
        $defaultItem = $default ? ['' => $defaultCaption] : [];

        return $defaultItem + [
            1 => 'Нет',
            2 => 'Да',
        ];
    }
}
