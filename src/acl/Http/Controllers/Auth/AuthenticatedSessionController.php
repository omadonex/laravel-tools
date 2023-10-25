<?php

declare(strict_types=1);

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Omadonex\LaravelTools\Acl\Events\UserLoggedIn;
use Omadonex\LaravelTools\Acl\Http\Requests\Auth\LoginRequest;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Support\Services\PageService;

class AuthenticatedSessionController extends Controller
{

    public function create(Request $request, PageService $pageService)
    {
        return $pageService->view($request, PageService::AUTH__LOGIN);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        list($field, $login, $user) = $request->authenticate();

        $request->session()->regenerate();
        
        event(new UserLoggedIn($user->getKey()));

        return redirect()->intended('/');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
