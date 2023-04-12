<?php

namespace Omadonex\LaravelTools\Locale\Classes\Utils;

use Illuminate\Database\Schema\Blueprint;
use Omadonex\LaravelTools\Support\Classes\ConstCustom;

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