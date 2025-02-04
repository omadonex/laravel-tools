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

    public const ROOT__TOOLS__ACL_ROUTE = 'Root_Tools_AclRoute';
    public const AUTH__LOGIN = 'Auth_Login';
    public const AUTH__REGISTER = 'Auth_Register';

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
        self::AUTH__LOGIN => [
            'title' => 'Вход',
        ],
        self::AUTH__REGISTER => [
            'title' => 'Регистрация',
        ],
    ];

    public static function data(string $pageIndex): array
    {
        return static::$pages[$pageIndex] ?? [];
    }

    public static function title(string $pageIndex, string $sub = '', array $routeParams = []): string
    {
        if ($sub === 'show') {
            return $routeParams[0];
        }

        return self::data($pageIndex)['title'] ?? '';
    }

    public static function icon(string $pageIndex): string
    {
        return self::data($pageIndex)['icon'];
    }

    public static function route(string $pageIndex, string $sub = '', array $routeParams = []): string
    {
        $finalPageIndex = $pageIndex . ($sub ? "_{$sub}" : '');

        return route(UtilsCustom::camelToDashed(str_replace('_', '.', $finalPageIndex)), $routeParams);
    }

    public static function navbarData(string $pageId, string $role = '', bool $noIcon = false, string $badge = ''): array
    {
        $data = self::data($pageId);

        $icon = [];
        $iconData = [];
        if (!$noIcon) {
            $icon = ($data['icon'] ?? null) ? ['icon' => $data['icon']] : [];
            $iconData = $data['iconData'] ?? [];
        }

        return array_merge([
            'name' => $data['title'],
            'route' => ($data['sub'] ?? false) ? "{$data['path']}.index" : $data['route'],
        ], $icon, $iconData, $role ? ['role' => $role] : [], $badge ? ['badge' => $badge] : []);
    }

    public function getPageId(string $pageIndex, string $sub = ''): string
    {
        return $pageIndex . ($sub ? '_' . Str::ucfirst($sub) : '');
    }

    protected function getViewName(string $pageIndex, string $sub = '')
    {
        if ($sub && !in_array($sub, self::data($pageIndex)['custom'] ?? [])) {
            switch ($sub) {
                case 'index': return 'omx-bootstrap::resource.index.template';
                case 'show': return 'omx-bootstrap::resource.show.template';
                case 'history': return 'omx-bootstrap::resource.history.template';
                case 'import': return 'omx-bootstrap::resource.import.template';
            }
        }

        $name = implode('.', array_map(function ($part) {
            $splitted = preg_split('/(?=[A-Z])/', $part);
            $splittedLower = array_map(function ($item) {
                return strtolower($item);
            }, $splitted);

            return implode('-', array_filter($splittedLower));
        }, explode('_', $pageIndex)));

        return $name . ($sub ? ".{$sub}" : '');
    }

    protected function getBreadcrumbs(string $pageIndex, ?Model $model, array $pageData, string $sub = '', array $breadcrumbReplaceData = []): array
    {
        $breadcrumbReplaceData['?'] = $model ? $model->getKey() : null;

        $breadcrumbs = $pageData['breadcrumbs'] ?? [];

        foreach ($breadcrumbs as $key => $breadcrumb) {
            foreach ($breadcrumb as $index => $item) {
                foreach ($breadcrumbReplaceData as $searth => $replace) {
                    $str = str_replace($searth, $replace, $item);
                }
                $breadcrumbs[$key][$index] = $str;
            }
        }

        return $breadcrumbs;
    }

    protected function getBreadcrumb(?Model $model, array $pageData, string $sub = '', array $breadcrumbReplaceData = []): string
    {
        $breadcrumbReplaceData['?'] = $model ? $model->getKey() : null;

        if ($pageData['resource'] ?? false) {
            if ($sub === 'history') {
                return self::BREADCRUMB_HISTORY;
            }

            if ($sub === 'show') {
                $str = self::BREADCRUMB_SHOW;
                foreach ($breadcrumbReplaceData as $searth => $replace) {
                    $str = str_replace($searth, $replace, $str);
                }

                return $str;
            }
        }

        $str = $pageData['breadcrumb'] ?? $pageData['title'];
        foreach ($breadcrumbReplaceData as $searth => $replace) {
            $str = str_replace($searth, $replace, $str);
        }

        return $str;
    }

    private function getModelView(array $pageData): ?ModelView
    {
        if ($pageData['resource'] ?? false) {
            return new $pageData['modelView'];
        }

        return null;
    }

    protected function getViewData(string $pageIndex, string $sub = '', array $data = [], array $breadcrumbReplaceData = [])
    {
        $user = $this->aclService->user();
        $pageId = $this->getPageId($pageIndex, $sub);
        $pageData = self::data($pageIndex);
        $model = $data['model'] ?? null;

        $options = [
            'page' => [
                'index' => $pageIndex,
                'id' => $pageId,
                'idBack' => $pageId, //TODO omadonex: ???
                'title' => $pageData['title'],
                'icon' => $pageData['icon'] ?? null,
                'iconData' => $pageData['iconData'] ?? null,
                'breadcrumbs' => $this->getBreadcrumbs($pageIndex, $model, $pageData, $sub, $breadcrumbReplaceData),
                'breadcrumb' => $this->getBreadcrumb($model, $pageData, $sub, $breadcrumbReplaceData),
                'tab' => $data['tab'] ?? null,
                'filter' => $data['filter'] ?? [],
                'model' => $model,
            ],
        ];

        unset($data['tab']);
        unset($data['filter']);
        unset($data['model']);

        if ($pageData['sub'] ?? false) {
            $options['res'] = [
                'path' => $pageData['path'] ?? null,
                'sub' => $sub,
                'subList' => $pageData['sub'],
            ];
        }

        if ($pageData['tableList'] ?? []) {
            $tableList = [];
            foreach ($pageData['tableList'] as $tableKey => $modeList) {
                $tableId = "{$pageId}__Table" . ucfirst($tableKey);
                $tableList[] = [
                    'index' => $tableKey,
                    'id' => $tableId,
                    'modeList' => $modeList,
                    'columnSetList' => $this->columnSetRepository->getList($user ? $user->getKey() : 0, $pageId, $tableId),
                ];
            }
            $options['tableList'] = $tableList;
        }

        return array_merge($data, [
            'user' => $user,
            'options' => $options,
        ]);
    }

    public function view(Request $request, string $pageIndex, string $sub = '', array $data = [], array $breadcrumbReplaceData = [])
    {
        $viewName = $this->getViewName($pageIndex, $sub);
        $data['filter'] = $this->getFilterPage($this->getPageId($pageIndex, $sub));

        return view($viewName, $this->getViewData($pageIndex, $sub, $data, $breadcrumbReplaceData));
    }
}