<?php

namespace Omadonex\LaravelTools\Support\Tools;


use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;

class Lists
{
    public static function get(string $listName, bool $addEmpty = true, array $empty = [], bool $closure = false): mixed
    {
        $func = function (array $params = []) use ($listName, $addEmpty, $empty) {
            $list = static::$listName();
            $list = is_array($list) ? $list : $list->toArray();

            if ($addEmpty) {
                $list = ($empty ?: ['' => 'Не выбрано']) + $list;
            }

            return $list;
        };

        return $closure ? $func : $func();
    }

    protected static function currency()
    {
        $data = app(ILocaleService::class)->getTranslatedCurrencyList();
        $list = [];
        foreach ($data as $item) {
            $list[$item['currency']] = $item['name'];
        }

        return $list;
    }

    protected static function currencySign()
    {
        $data = app(ILocaleService::class)->getTranslatedCurrencyList();
        $list = [];
        foreach ($data as $item) {
            $list[$item['currency']] = $item['sign'];
        }

        return $list;
    }


    protected static function user()
    {
        return app(UserRepository::class)->pluckExt();
    }

    protected static function yesNo()
    {
        return Custom::yesNoList(false);
    }
}
