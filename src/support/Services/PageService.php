<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;
use Omadonex\LaravelTools\Support\ModelView\ModelView;
use Omadonex\LaravelTools\Support\Repositories\ColumnSetRepository;
use Omadonex\LaravelTools\Support\Traits\GlobalFilterTrait;

class PageService extends OmxService
{
    use GlobalFilterTrait;

    public const AUTH_LOGIN = 'auth_login';
    public const AUTH_REGISTER = 'auth_register';

    protected const BREADCRUMB_HISTORY = 'История изменений';
    protected const BREADCRUMB_SHOW = 'Карточка записи (ID: ?)';

    protected IAclService $aclService;
    protected ColumnSetRepository $columnSetRepository;

    public function __construct(IAclService $aclService, ColumnSetRepository $columnSetRepository)
    {
        $this->aclService = $aclService;
        $this->columnSetRepository = $columnSetRepository;
    }

    protected static array $pages = [
        self::AUTH_LOGIN => [
            'title' => 'Вход',
        ],
        self::AUTH_REGISTER => [
            'title' => 'Регистрация',
        ],
    ];

    public static function data(string $pageIndex): array
    {
        return static::$pages[$pageIndex];
    }

    public static function title(string $pageIndex): string
    {
        return self::data($pageIndex)['title'] ?? '';
    }

    public static function icon(string $pageIndex): string
    {
        return self::data($pageIndex)['icon'];
    }

    public static function route(string $pageIndex, string $resourceSubPage = ''): string
    {
        $finalPageIndex = $pageIndex . ($resourceSubPage ? "_{$resourceSubPage}" : '');

        return route(UtilsCustom::camelToDashed(str_replace('_', '.', $finalPageIndex)));
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
            'route' => ($data['resource'] ?? false) ? "{$data['basePath']}.index" : $data['route'],
        ], $icon, $role ? ['role' => $role] : [], $badge ? ['badge' => $badge] : []);
    }

    protected function getPageId(string $pageIndex, string $resourceSubPage = ''): string
    {
        return Str::of($pageIndex)
            ->explode('_')
            ->map(fn ($part) => Str::ucfirst($part))
            ->implode('')
            . ($resourceSubPage ? Str::ucfirst($resourceSubPage) : '');
    }

    protected function getViewName(string $pageIndex, string $resourceSubPage = '')
    {
        if ($resourceSubPage && !in_array($resourceSubPage, self::data($pageIndex)['customView'] ?? [])) {
            switch ($resourceSubPage) {
                case 'index': return 'partials.resource.index.template';
                case 'show': return 'partials.resource.show.template';
                case 'history': return 'partials.resource.history.template';
            }
        }

        return str_replace('_', '.', $pageIndex) . ($resourceSubPage ? ".{$resourceSubPage}" : '');
    }

    protected function getTableList(string $pageIndex, string $resourceSubPage = ''): array
    {
        $pageId = $this->getPageId($pageIndex, $resourceSubPage);


        if (in_array($resourceSubPage, ['show', 'history'])) {
            $table = self::data($pageIndex)['tableList']['index'][0];
            if ($resourceSubPage === 'show') {
                return ["{$pageId}__tableModelHistory" => $table['title']];
            }

            return ["{$pageId}__tableHistory" => $table['title']];
        }

        $tables = self::data($pageIndex)['tableList'][$resourceSubPage] ?? [];
        $tableList = [];
        foreach ($tables as $index => $table)
        {
            $tableList["{$pageId}__table{$index}"] = $table['title'];
        }

        return $tableList;
    }

    protected function getBreadcrumbs(string $pageIndex, array $pageData, string $resourceSubPage = ''): array
    {
        if ($pageData['resource'] ?? false) {
            if ($resourceSubPage !== 'index') {
                return [
                    [$pageIndex, 'index'],
                ];
            }
        }

        return $pageData['breadcrumbs'] ?? [];
    }

    protected function getBreadcrumb(array $pageData, ?Model $model, string $resourceSubPage = ''): string
    {
        if ($pageData['resource'] ?? false) {
            if ($resourceSubPage === 'history') {
                return self::BREADCRUMB_HISTORY;
            }

            if ($resourceSubPage === 'show') {
                return str_replace('?', $model ? $model->getKey() : null, self::BREADCRUMB_SHOW);
            }
        }

        return str_replace('?', $model ? $model->getKey() : null, $pageData['breadcrumb'] ?? $pageData['title']);
    }

    private function getModelView(array $pageData): ?ModelView
    {
        if ($pageData['resource'] ?? false) {
            return new $pageData['modelView'];
        }

        return null;
    }

    private function getViewData(string $pageIndex, string $resourceSubPage = '', array $data = [])
    {
        $user = $this->aclService->user();
        $pageId = $this->getPageId($pageIndex, $resourceSubPage);
        $pageData = self::data($pageIndex);
        $tableList = $this->getTableList($pageIndex, $resourceSubPage);
        $defaultTableId = count($tableList) > 0 ? array_keys($tableList)[0] : '';
        $model = $data['model'] ?? null;

        return array_merge($data, [
            'user' => $user,
            'deniedModList' => $pageData['denied'] ?? [],
            'basePath' => $pageData['basePath'],
            'view' => $this->getModelView($pageData),
            'resourceSubPage' => $resourceSubPage,
            'pageId' => $pageId,
            'pageIdBack' => $pageId,
            'pageTitle' => $pageData['title'],
            'pageIcon' => $pageData['icon'] ?? null,
            'pageBreadcrumbs' => $this->getBreadcrumbs($pageIndex, $pageData, $resourceSubPage),
            'pageBreadcrumb' => $this->getBreadcrumb($pageData, $model, $resourceSubPage),
            'tableList' => $tableList,
            'tableId' => $defaultTableId,
            'tableTitle' => $tableList[$defaultTableId],
            'tableColumnSettingList' => $this->columnSetRepository->getList($user ? $user->getKey() : 0, $pageId, $defaultTableId),
            'modalTitleList' => $pageData['modal']['title'] ?? [],
            'modalWidthList' => $pageData['modal']['width'] ?? [],
        ]);
    }

    public function view(Request $request, string $pageIndex, string $resourceSubPage = '', array $data = [])
    {
        $viewName = $this->getViewName($pageIndex, $resourceSubPage);
        $data['filter'] = $this->getFilter($request, $pageIndex, $resourceSubPage);

        return view($viewName, $this->getViewData($pageIndex, $resourceSubPage, $data));
    }
}