<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Acl\Http\Requests\Auth\RegisterRequest;
use Omadonex\LaravelTools\Acl\Services\Model\UserService;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Constructor\Template\IPageService as Page;

class RegisteredUserController extends Controller
{

    public function create(Request $request, Page $page)
    {
        return $page->view($request, Page::AUTH__REGISTER);
    }

    public function store(RegisterRequest $request, UserService $userService)
    {
        $user = $userService->register($request->validated());
        Auth::login($user);

        return redirect('/');
    }
}
