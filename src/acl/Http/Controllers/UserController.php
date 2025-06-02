<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Omadonex\LaravelTools\Acl\Http\Requests\User\ChangePassRequest;
use Omadonex\LaravelTools\Acl\Http\Requests\User\RoleAttachRequest;
use Omadonex\LaravelTools\Acl\Http\Requests\User\RoleDetachRequest;
use Omadonex\LaravelTools\Acl\Http\Requests\User\UserRequest;
use Omadonex\LaravelTools\Acl\Http\Requests\User\UserUpdateRequest;
use Omadonex\LaravelTools\Acl\Interfaces\IRole;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Acl\Models\UserHistory;
use Omadonex\LaravelTools\Acl\Repositories\RoleRepository;
use Omadonex\LaravelTools\Acl\Repositories\UserRepository;
use Omadonex\LaravelTools\Acl\Resources\UserDatatablesResource;
use Omadonex\LaravelTools\Acl\Services\Model\UserService;
use Omadonex\LaravelTools\Acl\Transformers\UserTransformer;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Constructor\Template\IPageService as Page;
use Omadonex\LaravelTools\Support\Repositories\HistoryRepository;
use Omadonex\LaravelTools\Support\Tools\Noty;
use Omadonex\LaravelTools\Support\Traits\ConvertCheckboxValuesTrait;
use Omadonex\LaravelTools\Support\Traits\DatatablesResponseTrait;
use Omadonex\LaravelTools\Support\Traits\JsonResponseTrait;
use Omadonex\LaravelTools\Support\Transformers\HistoryTransformer;

class UserController extends Controller
{
    use ConvertCheckboxValuesTrait;
    use DatatablesResponseTrait;
    use JsonResponseTrait;

    public function index(Request $request, Page $page)
    {
        return $page->view($request, Page::OMX__USER, 'index');
    }

    public function data(Request $request, UserRepository $userRepository)
    {
        return $this->toDatatablesResponse($request, $userRepository, [
            'resource' => true,
            'resourceClass' => UserDatatablesResource::class,
        ], 'grid', UserTransformer::class);
    }

    public function history(Request $request, Page $page): Factory|View|Application
    {
        return $page->view($request, Page::OMX__USER, 'history');
    }

    public function historyData(Request $request)
    {
        $historyRepository = new HistoryRepository(new UserHistory);

        return $this->toDatatablesResponse($request, $historyRepository, [
            'resource' => true,
            'params' => [
                'table' => (new UserHistory)->getTable(),
                'lang' => app('locale')->getLocaleCurrent(),
            ],
        ], 'grid', HistoryTransformer::class, [
            'modelClass' => User::class,
            'modelShowUrl' => User::getRouteName('show'),
            'historyClass' => UserHistory::class,
        ]);
    }

    public function show($id, Request $request, UserRepository $userRepository, RoleRepository $roleRepository, Page $page)
    {
        $user = $userRepository->find($id, ['relations' => ['roles', 'roles.translates']]);
        $userRoleList = [];
        foreach ($user->roles as $role) {
            $userRoleList[$role->getKey()] = $role->getTranslate()->name;
        }

        $exceptRoleIdList = [IRole::ROOT, IRole::USER] + (app('acl')->isRoot() ? [] : [IRole::ROOT]);

        $unusedRoleList = $roleRepository->pluckUnusedRolesNames(trans('placeholders.filter_role_id'), $exceptRoleIdList);

        return $page->view($request, Page::OMX__USER, 'show', [
            'model' => $user,
            'userRoleList' => $userRoleList,
            'unusedRoleList' => $unusedRoleList,
            'tab' => $request->tab ?? 'main',
        ]);
    }

    public function edit($id, UserRepository $userRepository): JsonResponse
    {
        $userResource = $userRepository->find($id, ['resource' => true]);

        return $this->defaultJsonResponse($userResource);
    }

    public function store(UserRequest $request, UserService $userService)
    {
        $userService->create($request->validated(), false);

        return $this->defaultJsonResponse([], Noty::get('Пользователь успешно добавлен'));
    }

    public function update($id, UserUpdateRequest $request, UserService $userService): JsonResponse
    {
        $userService->update($id, $request->all());

        return $this->defaultJsonResponse([], Noty::get('Пользователь успешно сохранен'));
    }

    public function destroy($id, UserService $userService)
    {
        $userService->delete($id);

        return $this->defaultJsonResponse([], Noty::get('Пользователь успешно удален'));
    }

    public function roleStore($userId, RoleAttachRequest $request, UserService $userService)
    {
        $userService->attachRole($userId, $request->role_id);

        session()->flash('noty', Noty::get('Роль успешно назначена'));

        return redirect(route('root.acl.user.show', $userId) . '?tab=role');
    }

    public function roleDestroy($userId, RoleDetachRequest $request, UserService $userService)
    {
        $userService->detachRole($userId, $request->role_id);

        return $this->defaultJsonResponse([], Noty::get('Роль успешно отозвана'));
    }

    public function passStore($userId, ChangePassRequest $request, UserService $userService)
    {
        $userService->setPassword($userId, $request->password);

        session()->flash('noty', Noty::get('Пароль успешно обновлен'));

        return redirect(route('root.acl.user.show', $userId) . '?tab=main');
    }
}
