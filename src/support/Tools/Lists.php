<?php

namespace Omadonex\LaravelTools\Support\Tools;


use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Repositories\ConfigRepository;

class Lists
{
    public static function get(string $listName, bool $addEmpty = true, array $empty = [], bool $closure = false, array $arguments = []): mixed
    {
        $func = function (array $params = []) use ($listName, $addEmpty, $empty, $arguments) {
            $list = static::$listName(...$arguments);
            $list = is_array($list) ? $list : $list->toArray();

            if ($addEmpty) {
                $list = ($empty ?: ['' => 'Не выбрано']) + $list;
            }

            return $list;
        };

        return $closure ? $func : $func();
    }

    protected static function configValueType()
    {
        return ConfigRepository::VALUE_TYPE_LIST;
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
