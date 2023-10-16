<?php

namespace Omadonex\LaravelTools\Support\Tools;


class Noty
{
    public static function get(string $message, string $context = Context::SUCCESS)
    {
        return [
            'context' => $context,
            'message' => $message,
        ];
    }
}
