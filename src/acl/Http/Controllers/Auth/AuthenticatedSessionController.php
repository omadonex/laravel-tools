<?php

declare(strict_types=1);

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Acl\Events\UserLoggedIn;
use Omadonex\LaravelTools\Acl\Http\Requests\Auth\LoginRequest;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Constructor\Template\IPageService as Page;

class AuthenticatedSessionController extends Controller
{

    public function create(Request $request, Page $page)
    {
        return $page->view($request, Page::AUTH__LOGIN);
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
