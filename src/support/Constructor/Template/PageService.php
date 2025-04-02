<?php

namespace Omadonex\LaravelTools\Support\Constructor\Template;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;
use Omadonex\LaravelTools\Support\ModelView\ModelView;
use Omadonex\LaravelTools\Support\Repositories\ColumnSetRepository;
use Omadonex\LaravelTools\Support\Services\OmxService;
use Omadonex\LaravelTools\Support\Traits\GlobalFilterTrait;

abstract class PageService extends OmxService implements IPageService
{
    use GlobalFilterTrait;

    protected const BREADCRUMB_HISTORY = 'История изменений';
    protected const BREADCRUMB_SHOW = 'Карточка записи (ID: ?)';

    protected IAclService $aclService;
    protected ColumnSetRepository $columnSetRepository;

    public function __construct(IAclService $aclService, ColumnSetRepository $columnSetRepository)
    {
        $this->aclService = $aclService;
        $this->columnSetRepository = $columnSetRepository;
    }

    public static function getTable(array $tableList, string $tableIndex): array
    {
        return array_values(array_filter($tableList, function ($item) use ($tableIndex) {
            return $item['index'] == $tableIndex;
        }))[0];
    }

    public static function getTableIndexById(string $tableId): string
    {
        return explode('__Table', $tableId)[1];
    }

    public static function route(string $pageIndex, string $sub = '', array $routeParams = []): string
    {
        $finalPageIndex = $pageIndex . ($sub ? "_{$sub}" : '');

        return route(UtilsCustom::camelToDashed(str_replace('_', '.', $finalPageIndex)), $routeParams);
    }

    public function getPageIndexById(string $pageId): array
    {
        if ($this->data($pageId)) {
            return [$pageId, ''];
        }

        $parts = explode('_', $pageId);
        $sub = array_pop($parts);
        $pageIndex = implode('_', $parts);

        return [$pageIndex, $sub];
    }

    abstract protected function pages(): array;

    protected function pagesDefault(): array
    {
        return [
            self::AUTH__LOGIN => [
                'title' => 'Вход',
            ],
            self::AUTH__REGISTER => [
                'title' => 'Регистрация',
            ],
            self::OMX__RESOURCE__ROLE => [
                'sub' => ['index', 'show', 'history'],
                'title' => 'Роли',
                'path' => Role::getPath(),
                'tableList' => [
                    ITableService::ROLE => [
                        'create',
                        'edit',
                        'destroy',
                        'history',
                        'filter',
                    ],
                ],
            ],
            self::OMX__RESOURCE__USER => [
                'sub' => ['index', 'show'],
                'title' => 'Пользователи',
                'path' => User::getPath(),
                'tableList' => [
                    ITableService::USER => [
                        'create',
                        'edit',
                        'destroy',
                        'filter',
                    ],
                ],
                'custom' => [
                    'show',
                ],
            ],
        ];
    }

    public function data(string $pageIndex): array
    {
        $pages = $this->pagesDefault() + $this->pages();

        return $pages[$pageIndex] ?? [];
    }

    public function title(string $pageIndex, string $sub = '', array $routeParams = []): string
    {
        if ($sub === 'show') {
            return $routeParams[0];
        }

        return $this->data($pageIndex)['title'] ?? '';
    }

    public function icon(string $pageIndex): string
    {
        return $this->data($pageIndex)['icon'];
    }

    public function navbarData(string $pageId, string $role = '', bool $noIcon = false, string $badge = ''): array
    {
        $data = $this->data($pageId);

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
                $ext = app(ITableService::class)->data($tableKey);
                $ext['view'] = app($ext['modelView']);

                $tableList[] = [
                    'index' => $tableKey,
                    'id' => $tableId,
                    'modeList' => $modeList,
                    'columnSetList' => $this->columnSetRepository->getList($user ? $user->getKey() : 0, $pageId, $tableId),
                    'ext' => $ext,
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