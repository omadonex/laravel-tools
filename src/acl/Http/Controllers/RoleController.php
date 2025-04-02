<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Omadonex\LaravelTools\Acl\Http\Requests\Role\RoleRequest;
use Omadonex\LaravelTools\Acl\Models\Role;
use Omadonex\LaravelTools\Acl\Models\RoleHistory;
use Omadonex\LaravelTools\Acl\Repositories\RoleRepository;
use Omadonex\LaravelTools\Acl\Resources\RoleDatatablesResource;
use Omadonex\LaravelTools\Acl\Services\Model\RoleService;
use Omadonex\LaravelTools\Acl\Transformers\RoleTransformer;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Constructor\Template\IPageService as Page;
use Omadonex\LaravelTools\Support\Repositories\HistoryRepository;
use Omadonex\LaravelTools\Support\Tools\Noty;
use Omadonex\LaravelTools\Support\Traits\ConvertCheckboxValuesTrait;
use Omadonex\LaravelTools\Support\Traits\DatatablesResponseTrait;
use Omadonex\LaravelTools\Support\Traits\JsonResponseTrait;
use Omadonex\LaravelTools\Support\Transformers\HistoryTransformer;

class RoleController extends Controller
{
    use ConvertCheckboxValuesTrait;
    use DatatablesResponseTrait;
    use JsonResponseTrait;

    public function index(Request $request, Page $page)
    {
        return $page->view($request, Page::OMX__RESOURCE__ROLE, 'index');
    }

    public function data(Request $request, RoleRepository $roleRepository)
    {
        return $this->toDatatablesResponse($request, $roleRepository, [
            'resource' => true,
            'resourceClass' => RoleDatatablesResource::class,
        ], 'grid', RoleTransformer::class);
    }

    public function history(Request $request, Page $page): Factory|View|Application
    {
        return $page->view($request, Page::OMX__RESOURCE__ROLE, 'history');
    }

    public function historyData(Request $request)
    {
        $historyRepository = new HistoryRepository(new RoleHistory);

        return $this->toDatatablesResponse($request, $historyRepository, [
            'resource' => true,
            'params' => [
                'table' => (new RoleHistory)->getTable(),
                'lang' => app('locale')->getLocaleCurrent(),
            ],
        ], 'grid', HistoryTransformer::class, [
            'modelClass' => Role::class,
            'modelShowUrl' => 'root.acl.role.show',
            'historyClass' => RoleHistory::class,
        ]);
    }

    public function show($id, Request $request, RoleRepository $roleRepository, Page $page)
    {
        $model = $roleRepository->find($id, [
            'relations' => ['translates'],
        ]);

        return $page->view($request, Page::OMX__RESOURCE__ROLE, 'show', [
            'model' => $model,
            'tab' => $request->tab ?? 'main',
        ]);
    }

    public function edit($id, Request $request, RoleRepository $roleRepository)
    {
        /* @var \Omadonex\LaravelTools\Acl\Models\Role $role */
        $role = $roleRepository->find($id);
        $translate = $role->getTranslate();

        return $this->defaultJsonResponse($this->convertToCheckboxValues([
            'id' => $role->getKey(),
            'is_hidden' => $role->is_hidden,
            'is_staff' => $role->is_staff,
            'name' => $translate->name,
            'description' => $translate->description,
        ], ['is_hidden', 'is_staff']));
    }

    public function store(RoleRequest $request, RoleService $roleService)
    {
        $data = $this->convertFromCheckboxValues($request->validated(), ['is_hidden', 'is_staff']);

        $roleService->createWithT(app('locale')->getLocaleCurrent(), [
            'is_staff' => $data['is_staff'],
            'is_hidden' => $data['is_hidden'],
        ], [
            'name' => $data['name'],
            'description' => $data['description'],
        ], false);

        return $this->defaultJsonResponse([], Noty::get('Роль успешно добавлена'));
    }

    public function update($id, RoleRequest $request, RoleService $roleService)
    {
        $data = $this->convertFromCheckboxValues($request->validated(), ['is_hidden', 'is_staff']);

        $roleService->updateWithT($id, app('locale')->getLocaleCurrent(), [
            'is_staff' => $data['is_staff'],
            'is_hidden' => $data['is_hidden'],
        ], [
            'name' => $data['name'],
            'description' => $data['description'],
        ]);

        return $this->defaultJsonResponse([], Noty::get('Роль успешно сохранена'));
    }

    public function destroy($id, RoleService $roleService)
    {
        $roleService->deleteWithT($id);

        return $this->defaultJsonResponse([], Noty::get('Роль успешно удалена'));
    }
}
