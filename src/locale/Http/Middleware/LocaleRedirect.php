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
        $lang = $request->segment(1);

        if ($lang && $localeService->isLangCorrect($lang)) {
            if ($lang === $localeService->getLangDefault()) {
                session()->reflash();

                return new RedirectResponse($localeService->getUrlWithoutLang($request->fullUrl()), 302, ['Vary' => 'Accept-Language']);
            }

            if ($localeService->isLangSupported($lang)) {
                session(['lang' => $lang]);

                return $next($request);
            }

            abort(406);
        }

        return $next($request);
    }
}
