<?php

namespace Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Constructor\Template\IPageService as Page;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Http\Requests\ConfigRequest;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Models\Config;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Models\ConfigHistory;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Repositories\ConfigRepository;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Resources\Datatables\ConfigDatatablesResource;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Services\ConfigService;
use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Transformers\ConfigTransformer;
use Omadonex\LaravelTools\Support\Repositories\HistoryRepository;
use Omadonex\LaravelTools\Support\Tools\Noty;
use Omadonex\LaravelTools\Support\Traits\DatatablesResponseTrait;
use Omadonex\LaravelTools\Support\Traits\JsonResponseTrait;
use Omadonex\LaravelTools\Support\Transformers\HistoryTransformer;

class ConfigController extends Controller
{
    use DatatablesResponseTrait;
    use JsonResponseTrait;

    public function index(Request $request, Page $page): Factory|View|Application
    {
        return $page->view($request, Page::OMX__CONFIG, 'index');
    }

    public function data(Request $request, ConfigRepository $configRepository)
    {
        return $this->toDatatablesResponse($request, $configRepository, [
            'resource' => true,
            'resourceClass' => ConfigDatatablesResource::class,
        ], 'grid', ConfigTransformer::class);
    }

    public function history(Request $request, Page $page): Factory|View|Application
    {
        return $page->view($request, Page::OMX__CONFIG, 'history');
    }

    public function historyData(Request $request)
    {
        $historyRepository = new HistoryRepository(new ConfigHistory);

        return $this->toDatatablesResponse($request, $historyRepository, [
            'resource' => true,
            'params' => [
                'table' => (new ConfigHistory)->getTable(),
                'lang' => app('locale')->getLocaleCurrent(),
            ],
        ], 'grid', HistoryTransformer::class, [
            'modelClass' => Config::class,
            'historyClass' => ConfigHistory::class,
        ]);
    }

    public function edit($id, ConfigRepository $configRepository): JsonResponse
    {
        return $this->defaultJsonResponse($configRepository->find($id, ['resource' => true]));
    }

    public function update($id, ConfigRequest $request, ConfigService $configService): JsonResponse
    {
        $configService->update($id, $request->all());

        return $this->defaultJsonResponse([], Noty::get('Конфиг успешно обновлен'));
    }
}
