<?php

namespace Omadonex\LaravelTools\Support\Tools;


class Caption
{
    const EMPTY = '&mdash;';
    const RUB = '₽';
    const CROPPED_TEXT_LENGTH = 175;

    public static function cropped(string $text, int $length): string
    {
        return mb_substr($text, 0, $length) . '...';
    }
}
