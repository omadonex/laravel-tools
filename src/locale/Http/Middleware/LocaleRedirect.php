<?php

namespace Omadonex\LaravelTools\Locale\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
        
        $redirectIfNoLocale = config('omx.locale.redirectIfNoLocale');
        if ($locale && $localeService->isLocaleCorrect($locale)) {
            if ($locale === $localeService->getLocaleDefault()) {
                if ($redirectIfNoLocale) {
                    return $next($request);
                }

                session()->reflash();

                return new RedirectResponse($localeService->getUrlWithoutLocale($request->fullUrl()), Response::HTTP_FOUND, ['Vary' => 'Accept-Language']);
            }

            if ($localeService->isLocaleSupported($locale)) {
                session(['locale' => $locale]);

                return $next($request);
            }

            abort(406);
        }

        if ($redirectIfNoLocale) {
            $url = $localeService->getRouteLangList($request->fullUrl())[$localeService->getLocaleDefault()]['url'];

            return new RedirectResponse($url, Response::HTTP_FOUND, ['Vary' => 'Accept-Language']);
        }

        session(['locale' => $localeService->getLocaleDefault()]);

        return $next($request);
    }
}
