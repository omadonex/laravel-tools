<?php

namespace Omadonex\LaravelTools\Support\Commands;

use Illuminate\Console\Command;
use Omadonex\LaravelTools\Common\Classes\Stub;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsNames;

class ModelBootstrap extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omx:support:model-bootstrap
        {model : The name of the model, e.g. Document}
        {--S|space= The part of path, e.g. Root/Dictionary/Unit}
        {--D|description= The description of the model}
        {--H|history=false}
        ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all files for bootstraping model';

    protected $classList = [
        'modelView' => 'View',
        'repository' => 'Repository',
        'request' => 'Request',
        'resource' => 'Resource',
        'resourceDatatables' => 'DatatablesResource',
        'service' => 'Service',
        'transformer' => 'Transformer',
    ];

    protected $viewList = [
        'form' => '_form.blade.php',
    ];
    
    protected $assetList = [
        'index' => 'index.ts',
        'show' => 'show.ts',
    ];

    protected $namespaceClassList = [];
    protected $pathClassList = [];
    protected $pathViewList = [];
    protected $pathAssetList = [];
    protected $pathStubs = '';
    protected $model;
    protected $space = '';
    protected $desc = '';
    protected $historyEnabled = false;

    protected $tableConst = '';
    protected $pageConst = '';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $pathRoot = realpath(__DIR__.'/../../..');
        $this->pathStubs = "{$pathRoot}/resources/stubs";
        $config = config('omx.support.support.stubs');

        $this->model = $this->argument('model');
        $this->space = $this->option('space');
        $this->desc = $this->option('description');
        $this->historyEnabled = UtilsCustom::strictStrToBool($this->option('history'));

        if ($this->historyEnabled) {
            $this->classList['controllerWithHistory'] = 'Controller';
            $this->classList['modelWithHistory'] = '';
            $this->classList['history'] = 'History';
            $this->assetList['history'] = 'history.ts';
        } else {
            $this->classList['controller'] = 'Controller';
            $this->classList['model'] = '';
        }

        $this->setNamespaceClassList($config, $this->space);
        $this->addConstructorPages();
        $this->setPathLists($config, $this->space);
        $this->proceedClassList($this->model, $this->desc);
        $this->proceedViewList($this->model, $this->desc);
        $this->proceedAssetList($this->model, $this->desc);

        return 0;
    }

    private function proceedClassList(string $model, string $description): void
    {
        foreach ($this->classList as $section => $subName) {
            $contents = $this->getContents($section, $model, $subName, $description);
            $filePath = $this->getSectionFilePath($section, $model, $subName);

            if (!file_exists($filePath)) {
                file_put_contents($filePath, $contents);
            }
        }
    }

    private function proceedViewList(string $model, string $description): void
    {
        foreach ($this->viewList as $view => $name) {
            $contents = $this->getContents($view, $model, $name, $description);
            $filePath = $this->getViewFilePath($view, $name);

            if (!file_exists($filePath)) {
                file_put_contents($filePath, $contents);
            }
        }
    }

    private function proceedAssetList(string $model, string $description): void
    {
        foreach ($this->assetList as $asset => $name) {
            $contents = $this->getContents($asset, $model, $name, $description);
            $filePath = $this->getAssetFilePath($asset, $name);

            if (!file_exists($filePath)) {
                file_put_contents($filePath, $contents);
            }
        }
    }

    private function setNamespaceClassList(array $config, string $space): void
    {
        $namespaceList = [];
        $namespaceSub = implode('\\', explode('/', $space));
        foreach ($this->classList as $key => $subName) {
            $namespaceBase = $config['class'][$key];
            $namespaceList[$key] = $namespaceBase . ($namespaceSub ? "\\{$namespaceSub}" : '');
        }

        $this->namespaceClassList = $namespaceList;
    }

    private function setPathLists(array $config, string $space): void
    {
        $pathList = [];
        foreach ($this->classList as $key => $subName) {
            $namespaceBase = $config['class'][$key];
            $dirPath = explode('\\', $namespaceBase);
            $dirPath[0] = strtolower($dirPath[0]);
            $pathList[$key] =  base_path(implode('/', $dirPath)) . ($space ? "/{$space}" : '');
            if (!is_dir($pathList[$key])) {
                mkdir($pathList[$key], recursive: true);
            }
        }
        $this->pathClassList = $pathList;

        $spaceDashed = strtolower(UtilsCustom::camelToDashed($space));
        $modelDashedLower = strtolower(UtilsCustom::camelToDashed($this->model));

        $pathList = [];
        foreach ($this->viewList as $key => $subName) {
            $pathBase = $config['view'][$key];
            $pathList[$key] = base_path($pathBase) . ($spaceDashed ? "/{$spaceDashed}" : '') . "/{$modelDashedLower}";
            if (!is_dir($pathList[$key])) {
                mkdir($pathList[$key], recursive: true);
            }
        }
        $this->pathViewList = $pathList;

        $pathList = [];
        foreach ($this->assetList as $key => $subName) {
            $pathBase = $config['asset'][$key];
            $pathList[$key] = base_path($pathBase) . ($spaceDashed ? "/{$spaceDashed}" : '') . "/{$modelDashedLower}";
            if (!is_dir($pathList[$key])) {
                mkdir($pathList[$key], recursive: true);
            }

            $tsClass = str_replace('/', '', $space) . $this->model . ucfirst($key);
            $tsPath = "{$spaceDashed}/{$modelDashedLower}/{$key}";
            $importLine = "import {$tsClass} from './pages/{$tsPath}';";

            UtilsCustom::insertLine(base_path('resources/assets/ts/_pages.ts'), '/export const/', $importLine, false);
            UtilsCustom::insertLine(base_path('resources/assets/ts/_pages.ts'), '/];/', "    {$tsClass},", false);
        }
        $this->pathAssetList = $pathList;
    }

    private function getStubPath(string $key): string
    {
        if (array_key_exists($key, $this->pathClassList)) {
            return "{$this->pathStubs}/{$key}.stub";
        }

        if (array_key_exists($key, $this->pathViewList)) {
            return "{$this->pathStubs}/views/{$key}.stub";
        }

        if (array_key_exists($key, $this->pathAssetList)) {
            return "{$this->pathStubs}/assets/{$key}.stub";
        }

        return '';
    }

    private function getContents(string $key, string $model, string $subName, string $description): string
    {
        return (new Stub($this->getStubPath($key), [
            'HISTORY_ENABLED' => UtilsCustom::strictBoolToStr($this->historyEnabled),
            'NAMESPACE' => $this->namespaceClassList[$key] ?? '',
            'NAMESPACE_MODEL' => $this->namespaceClassList[$this->historyEnabled ? 'modelWithHistory' : 'model'] . "\\{$model}",
            'NAMESPACE_MODEL_VIEW' => $this->namespaceClassList['modelView'] . "\\{$model}View",
            'NAMESPACE_RESOURCE' => $this->namespaceClassList['resource'] . "\\{$model}Resource",
            'NAMESPACE_RESOURCE_DATATABLES' => $this->namespaceClassList['resourceDatatables'] . "\\{$model}DatatablesResource",
            'NAMESPACE_REPOSITORY' => $this->namespaceClassList['repository'] . "\\{$model}Repository",
            'NAMESPACE_REQUEST' => $this->namespaceClassList['request'] . "\\{$model}Request",
            'NAMESPACE_SERVICE' => $this->namespaceClassList['service'] . "\\{$model}Service",
            'NAMESPACE_TRANSFORMER' => $this->namespaceClassList['transformer'] . "\\{$model}Transformer",
            'MODEL' => $model,
            'MODEL_CAMELCASE' => lcfirst($model),
            'MODEL_FULL_PATH_UNDERSCORE' => implode('_', explode('/', $this->space)) . "_{$model}",
            'CLASS' => "{$model}{$subName}",
            'TABLE' => strtolower(UtilsNames::camelToUnderscore($model)),
            'DESCRIPTION' => $description,
            'PAGE_CONST' => $this->pageConst,
        ]
        + ($this->historyEnabled ? ['NAMESPACE_MODEL_HISTORY' => $this->namespaceClassList['history'] . "\\{$model}History"] : [])
        ))->render();
    }

    private function getSectionFilePath(string $key, string $model, string $subName): string
    {
        return "{$this->pathClassList[$key]}/{$model}{$subName}.php";
    }

    private function getViewFilePath(string $key, string $name): string
    {
        return "{$this->pathViewList[$key]}/{$name}";
    }

    private function getAssetFilePath(string $key, string $name): string
    {
        return "{$this->pathAssetList[$key]}/{$name}";
    }

    private function addConstructorPages(): void
    {
        $modelDashedUpper = strtoupper(UtilsNames::camelToUnderscore($this->model));
        $spaceDashed = implode('_', explode('/', $this->space));
        $spaceDoubleDashedUpper = [];
        foreach (explode('/', $this->space) as $part) {
            $spaceDoubleDashedUpper[] = strtoupper(UtilsNames::camelToUnderscore($part));
        };
        $spaceDoubleDashedUpper = implode('__', $spaceDoubleDashedUpper);

        $this->tableConst = $modelDashedUpper;
        $this->pageConst = "{$spaceDoubleDashedUpper}__{$modelDashedUpper}";
        $tableConstStr = "    const {$this->tableConst} = '{$this->model}';";
        $pageConstStr = "    const {$this->pageConst} = '{$spaceDashed}_{$this->model}';";

        UtilsCustom::insertLine(base_path('app/Constructor/Template/ITable.php'), '/}/', $tableConstStr, false);
        UtilsCustom::insertLine(base_path('app/Constructor/Template/IPage.php'), '/}/', $pageConstStr, false);

        $modelViewNamespace = $this->namespaceClassList['modelView'] . "\\{$this->model}View";
        $insertLine = "use {$modelViewNamespace};";
        UtilsCustom::insertLine(base_path('app/Constructor/Template/Table.php'), '/class Table/', $insertLine, false);
        $insertText = <<<EOD
                    self::{$this->tableConst} => [
                        'modelView' => {$this->model}View::class,
                        'title' => '',
                        'path' => '',
                        'captions' => [
                            'create' => 'Создание {$this->model}',
                            'edit' => 'Редактирование {$this->model}',
                        ],
                    ],
        EOD;
        UtilsCustom::insertLine(base_path('app/Constructor/Template/Table.php'), '/];/', $insertText, false);

        $insertText = <<<EOD
                    self::{$this->pageConst} => [
                        'sub' => ['index', 'show'],
                        'title' => '',
                        'path' => '',
                        'icon' => 'streamline.regular.add',
                        'tableList' => [
                            Table::{$this->tableConst} => [
                                'create', 'edit', 'destroy',
                            ],
                        ],
                    ],
        EOD;
        UtilsCustom::insertLine(base_path('app/Constructor/Template/Page.php'), '/];/', $insertText, false);
    }
}
