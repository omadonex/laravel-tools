<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Support\Str;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class PageService
{
    const AUTH_LOGIN = 'auth_login';
    const AUTH_REGISTER = 'auth_register';

    protected static array $pages = [
        self::AUTH_LOGIN => [
            'title' => 'Вход',
        ],
        self::AUTH_REGISTER => [
            'title' => 'Регистрация',
        ],
    ];

    public static function getUniqueId(string $viewPath): string
    {
        return Str::of($viewPath)
            ->explode('.')
            ->map(fn ($part) => Str::ucfirst($part))
            ->implode('');
    }

    public static function data(string $pageId): array
    {
        return static::$pages[$pageId];
    }

    public static function title(string $pageId): string
    {
        return self::data($pageId)['title'] ?? '';
    }

    public static function icon(string $pageId): string
    {
        return self::data($pageId)['icon'];
    }

    public static function route(string $pageId): string
    {
        return route(UtilsCustom::camelToDashed(str_replace('_', '.', $pageId)));
    }

    public static function navbarData(string $pageId, string $role = '', bool $noIcon = false, string $badge = ''): array
    {
        $data = self::data($pageId);

        $icon = [];
        if (!$noIcon) {
            $icon = ($data['icon'] ?? null) ? ['icon' => $data['icon']] : [];
        }

        return array_merge([
            'name' => $data['title'],
            'route' => $data['navbarRouteName'],
        ], $icon, $role ? ['role' => $role] : [], $badge ? ['badge' => $badge] : []);
    }

    protected function getTableId(string $pageId): string
    {
        $viewName = str_replace('_', '.', $pageId);
        $nameList = explode('.', $viewName);
        $pageName = count($nameList) > 1 ? ucfirst($nameList[count($nameList) - 2]) : '';

        return self::getUniqueId($viewName).'__table'.$pageName;
    }

    public function view(string $pageId, array $data = [])
    {
        $viewName = str_replace('_', '.', $pageId);
        $pageData = self::data($pageId);
        $model    = $data['model'] ?? null;
        $tableId  = $this->getTableId($pageId);

        return view($viewName, array_merge($data, [
            'pageIdBack' => $pageId,
            'pageId' => self::getUniqueId($viewName),
            'tableId' => $tableId,
            'pageTitle' => $pageData['title'],
            'pageIcon' => $pageData['icon'] ?? null,
            'pageBreadcrumbs' => $pageData['breadcrumbs'] ?? [],
            'pageBreadcrumb' => str_replace('?', $model ? $model->getKey() : null, $pageData['breadcrumb'] ?? $pageData['title']),
        ]));
    }
}