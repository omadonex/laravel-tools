<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Exception;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Acl\Http\Requests\Auth\RegisterRequest;
use Omadonex\LaravelTools\Acl\Services\UserService;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Services\PageService;

class RegisteredUserController extends Controller
{

    public function create(PageService $pageService)
    {
        return $pageService->view(PageService::AUTH_REGISTER);
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
