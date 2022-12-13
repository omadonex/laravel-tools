<?php

namespace Omadonex\LaravelTools\Support\Http\Middleware;

use App\Models\User;
use Closure;

class TryAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $apiToken = $request->bearerToken();
        if ($apiToken) {
            $user = User::where('api_token', $apiToken)->first();
            if ($user) {
                auth()->login($user);
            }
        }

        return $next($request);
    }
}
