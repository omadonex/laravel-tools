<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Acl\Http\Requests\Auth\RegisterRequest;
use Omadonex\LaravelTools\Acl\Services\Model\UserService;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Services\PageService;

class RegisteredUserController extends Controller
{

    public function create(Request $request, PageService $pageService)
    {
        return $pageService->view($request, PageService::AUTH__REGISTER);
    }

    public function store(RegisterRequest $request, UserService $userService)
    {
        try {
            $user = $userService->register($request->validated());
            Auth::login($user);
        } catch (Exception $e) {
            $this->catchAndThrowException($e);
        }

        return redirect('/');
    }
}
