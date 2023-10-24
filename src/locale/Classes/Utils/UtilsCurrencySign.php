<?php

namespace Omadonex\LaravelTools\Locale\Classes\Utils;

class UtilsCurrencySign
{
    public static function get(string $currency): string
    {
        return [
            'rub' => '&#8381;',
            'usd' => '&#36;',
        ][$currency] ?? '';
    }
}