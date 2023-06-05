<?php

namespace Omadonex\LaravelTools\Common\Tools;


class Avatar
{
    const NONE = '/img/none_avatar.png';

    public static function get($avatar)
    {
        return url($avatar ?: self::NONE);
    }
}
