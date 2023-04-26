<?php

declare(strict_types=1);

namespace Omadonex\LaravelTools\Acl\Http\Controllers\Auth;

use Omadonex\LaravelTools\Acl\Http\Requests\Auth\LoginRequest;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Omadonex\LaravelTools\Support\Services\PageService;

class AuthenticatedSessionController extends Controller
{

    public function create(PageService $pageService)
    {
        return $pageService->view(PageService::AUTH_LOGIN);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

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