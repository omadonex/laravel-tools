<?php

namespace Omadonex\LaravelTools\Locale\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;

class LocaleRedirect
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        /** @var ILocaleService $localeService */
        $localeService = app(ILocaleService::class);
        $locale = $request->segment(1);

        if ($locale && $localeService->isLocaleCorrect($locale)) {
            if ($locale === $localeService->getLocaleDefault()) {
                session()->reflash();

                return new RedirectResponse($localeService->getUrlWithoutLocale($request->fullUrl()), 302, ['Vary' => 'Accept-Language']);
            }

            if ($localeService->isLocaleSupported($locale)) {
                session(['locale' => $locale]);

                return $next($request);
            }

            abort(406);
        }

        return $next($request);
    }
}
