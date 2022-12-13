<?php

namespace Omadonex\LaravelTools\Acl\Http\Middleware;

use Closure;
use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

class AclInit {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        /** @var IAclService $aclService */
        $aclService = app(IAclService::class);

        if ($request->user()) {
            $aclService->setUser($request->user());
        }

        return $next($request);
    }
}
