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

    public static function route(string $pageIndex, string $sub = ''): string
    {
        $finalPageIndex = $pageIndex . ($sub ? "_{$sub}" : '');

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
            'route' => ($data['sub'] ?? false) ? "{$data['path']}.index" : $data['route'],
        ], $icon, $role ? ['role' => $role] : [], $badge ? ['badge' => $badge] : []);
    }

    public function getPageId(string $pageIndex, string $sub = ''): string
    {
        return Str::of($pageIndex)
            ->explode('_')
            ->map(fn ($part) => Str::ucfirst($part))
            ->implode('')
            . ($sub ? Str::ucfirst($sub) : '');
    }

    protected function getViewName(string $pageIndex, string $sub = '')
    {
        if ($sub && !in_array($sub, self::data($pageIndex)['custom'] ?? [])) {
            switch ($sub) {
                case 'index': return 'partials.resource.index.template';
                case 'show': return 'partials.resource.show.template';
                case 'history': return 'partials.resource.history.template';
            }
        }

        $name = implode('.', array_map(function ($item) {
            return implode('-', array_map(function ($subItem) {
                return strtolower($subItem);
            }, preg_split('/(?=[A-Z])/', $item)));
        }, explode('_', $pageIndex)));

        return $name . ($sub ? ".{$sub}" : '');
    }

    protected function getBreadcrumbs(string $pageIndex, array $pageData, string $sub = ''): array
    {
        if ($pageData['resource'] ?? false) {
            if ($sub !== 'index') {
                return [
                    [$pageIndex, 'index'],
                ];
            }
        }

        return $pageData['breadcrumbs'] ?? [];
    }

    protected function getBreadcrumb(array $pageData, ?Model $model, string $sub = ''): string
    {
        if ($pageData['resource'] ?? false) {
            if ($sub === 'history') {
                return self::BREADCRUMB_HISTORY;
            }

            if ($sub === 'show') {
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

    protected function getViewData(string $pageIndex, string $sub = '', array $data = [])
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
                'breadcrumbs' => $this->getBreadcrumbs($pageIndex, $pageData, $sub),
                'breadcrumb' => $this->getBreadcrumb($pageData, $model, $sub),
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
                $tableId = "{$pageId}__table" . ucfirst($tableKey);
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

    public function view(Request $request, string $pageIndex, string $sub = '', array $data = [])
    {
        $viewName = $this->getViewName($pageIndex, $sub);
        $data['filter'] = $this->getFilterPage($this->getPageId($pageIndex, $sub));

        return view($viewName, $this->getViewData($pageIndex, $sub, $data));
    }
}