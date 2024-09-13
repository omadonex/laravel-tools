<?php

namespace Omadonex\LaravelTools\Support\Tools;

use Carbon\Carbon;
use Omadonex\LaravelTools\Locale\Classes\Utils\UtilsCurrencySign;

class Convert
{
    public static function toMoney(?float $value, ?string $currency = '', bool $useEmptyCaption = false, int $digits = 2, string $spacer = ' ')
    {
        if ($useEmptyCaption && $value === null) {
            return Caption::EMPTY;
        }

        $str = number_format($value, $digits, ',', $spacer);
        if ($currency) {
            $str .= ' ' . UtilsCurrencySign::get($currency);
        }

        return $str;
    }

    public static function toDatetime($value, $format = 'd.m.Y H:i:s', $timezone = 'Europe/Moscow')
    {
        return Carbon::parse($value)->timezone($timezone)->format($format);
    }
}
