<?php

namespace Omadonex\LaravelTools\Acl\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

class Acl {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        $routeName = $request->route()->getName();
        $user = $request->user();

        /** @var IAclService $aclService */
        $aclService = app(IAclService::class);
        $aclService->setUser($user);

        if ($aclService->checkRoute($routeName)) {
            return $next($request);
        }

        abort(Response::HTTP_FORBIDDEN);
    }
}
