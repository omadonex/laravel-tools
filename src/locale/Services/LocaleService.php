<?php

namespace Omadonex\LaravelTools\Locale\Services;

use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class LocaleService implements ILocaleService
{
    private array $moduleList;

    private array $localeList;
    private array $currencyList;

    private array $langWholeList;
    private array $countryWholeList;
    private array $currencyWholeList;

    protected array $entryMap = [];

    /**
     * Locale constructor.
     * @param array $moduleList
     */
    public function __construct(array $moduleList = [])
    {
        $this->moduleList = $moduleList;

        $this->localeList = config('omx.locale.localeList');
        $this->currencyList = config('omx.locale.currencyList');

        $this->langWholeList = config('omx.localeLangList');
        $this->countryWholeList = config('omx.localeCountryList');
        $this->currencyWholeList = config('omx.localeCurrencyList');
    }

    public function getLocaleDefault(): string
    {
        return config('app.fallback_locale');
    }

    public function getLocaleCurrent(): string
    {
        return app()->getLocale();
    }

    public function getCurrencyDefault(): string
    {
        return config('omx.locale.currencyDefault');
    }

    public function isLocaleCorrect(string $locale): bool
    {
        return in_array($locale, $this->langWholeList);
    }

    public function isCountryCorrect(string $country): bool
    {
        return in_array($country, $this->countryWholeList);
    }

    public function isCurrencyCorrect(string $currency): bool
    {
        return in_array($currency, $this->currencyWholeList);
    }

    public function isLocaleSupported(string $lang): bool
    {
        return in_array($lang, $this->localeList);
    }

    private function getLocaleFact(string $locale = null): string
    {
        if (($locale === null) || !$this->isLocaleSupported($locale)) {
            return $this->getLocaleDefault();
        }

        return $locale;
    }

    public function getTranslatedLangList(array $langList = [], string $locale = null, bool $addNative = true): array
    {
        $langList = $langList ?: $this->localeList;
        $locale = $this->getLocaleFact($locale);

        $list = [];
        foreach ($langList as $lang) {
            $item = [
                'lang' => $lang,
                'name' => __("omx-locale::lang.{$lang}", [], $locale),
            ];

            if ($addNative) {
                $item['native'] = __("omx-locale::lang.{$lang}", [], $lang);
            }

            $list[] = $item;
        }

        return $list;
    }

    public function getTranslatedCurrencyList(array $currencyList = [], string $locale = null): array
    {
        $currencyList = $currencyList ?: $this->currencyList;
        $locale = $this->getLocaleFact($locale);

        $list = [];
        foreach ($currencyList as $currency) {
            $list[] = [
                'currency' => $currency,
                'name' => __("omx-locale::currency.{$currency}", [], $locale),
            ];
        }

        return $list;
    }

    public function getTranslatedCountryList(array $countryList = [], string $locale = null): array
    {
        $countryList = $countryList ?: $this->countryWholeList;
        $locale = $this->getLocaleFact($locale);

        $list = [];
        foreach ($countryList as $country) {
            $list[] = [
                'country' => $country,
                'name' => __("omx-locale::country.{$country}", [], $locale),
            ];
        }

        return $list;
    }

    public function setLocaleFromRoute(): ?string
    {
        $locale = request()->segment(1);
        if ($locale && $this->isLocaleCorrect($locale)) {
            $locale = $this->isLocaleSupported($locale) ? $locale : null;
            $localeFact = $this->getLocaleFact($locale);
            if ($localeFact != $this->getLocaleCurrent()) {
                app()->setLocale($localeFact);
            }

            return $locale;
        }

        app()->setLocale($this->getLocaleDefault());

        return null;
    }

    public function getLangWholeList(): array
    {
        return $this->langWholeList;
    }

    public function getCountryWholeList(): array
    {
        return $this->countryWholeList;
    }

    public function getCurrencyWholeList(): array
    {
        return $this->currencyWholeList;
    }

    public function getUrlWithoutLocale(string $url): string
    {
        $parsed = parse_url($url);
        if (!array_key_exists('path', $parsed)) {
            return $url;
        }

        $segments = explode('/', $parsed['path']);
        $locale = $segments[1];

        if ($this->isLocaleCorrect($locale)) {
            if (count($segments) > 2) {
                return preg_replace("/\/{$locale}\//", '/', $url, 1);
            }

            return preg_replace("/\/{$locale}/", '', $url, 1);
        }

        return $url;
    }

    public function getRouteLangList(string $url): array
    {
        $currentUrlWithoutLang = $this->getUrlWithoutLocale($url);
        $parsed = parse_url($currentUrlWithoutLang);
        $path = $parsed['path'] ?? '';

        $list = [];
        $redirectIfNoLocale = config('omx.locale.redirectIfNoLocale', true);
        foreach ($this->getTranslatedLangList() as $langItem) {
            if ($langItem['lang'] !== $this->getLocaleDefault()) {
                $parsed['path'] = "/{$langItem['lang']}{$path}";
            } else {
                $parsed['path'] = $redirectIfNoLocale ? "/{$langItem['lang']}{$path}" : $path;
            }

            $list[$langItem['lang']] = [
                'lang' => $langItem['lang'],
                'name' => $langItem['native'],
                'url' => UtilsCustom::buildUrl($parsed),
                'flag' => $this->getFlag($langItem['lang']),
            ];
        }

        return $list;
    }

    //TODO omadonex: check method
    public function route(string $name, $parameters = [], bool $absolute = true): string
    {
        $url = route($name, $parameters, $absolute);

        $locale = $this->getLocaleCurrent();
        if ($locale === $this->getLocaleDefault()) {
            return $url;
        }

        $parsed = parse_url($url);
        $parsed['path'] = "/{$locale}{$parsed['path']}";

        return UtilsCustom::buildUrl($parsed);
    }

    public function getFlag(string $lang): string
    {
        switch ($lang) {
            case 'en': return 'us';
        }

        return $lang;
    }

    public function getFlagCurrent(): string
    {
        return $this->getFlag($this->getLocaleCurrent());
    }

    private function getEntryMap(string $entry): array
    {
        $data = array_merge($this->entryMap, [
            self::ENTRY_AUTH => [],
            self::ENTRY_ALL => ['*'],
        ]);

        if (array_key_exists($entry, $data)) {
            return $data[$entry];
        }

        return $data;
    }

//    public function getEntryData(string $lang = null, string $entry = self::ENTRY_ALL): array
//    {
//        $totalData = $this->getTotalData($lang);
//        $entryList = $this->getEntryMap($entry);
//
//        if (count($entryModuleList)) {
//            if ($entryModuleList[0] === '*') {
//                $entryModuleList = array_keys($this->moduleList);
//            } elseif ($entryModuleList[0] === '^') {
//                $entryModuleList = array_diff(array_keys($this->moduleList), array_slice($entryModuleList, 0));
//            }
//        }
//    }

    public function getTotalData(string $locale = null): array
    {
        $data = [];

        $localeList = $locale ? [$locale] : $this->localeList;
        foreach ($localeList as $localeItem) {
            $data[$localeItem]['app'] = $this->getTranslations($localeItem);
            $data[$localeItem]['vendor'] = $this->getTranslationsVendor($localeItem);
            foreach ($this->moduleList as $module) {
                $trans = $this->getTranslations($localeItem, $module);
                if (!is_null($trans)) {
                    $data[$localeItem][$module->getLowerName()] = $trans;
                }
            }
        }

        return $data;
    }

    private function getTranslations(string $locale, $module = null): array
    {
        $trans = [];
        if (is_null($module)) {
            $pathPart = "lang/{$this->getLocaleDefault()}";
            $path = base_path($pathPart);
        } else {
            $pathPart = "Resources/lang/{$this->getLocaleDefault()}";
            $path = $module->getExtraPath($pathPart);
        }

        if (is_dir($path)) {
            $files = scandir($path);
            unset($files[0]);
            unset($files[1]);
            foreach ($files as $file) {
                $name = explode('.', $file)[0];
                $filePathPart = "{$pathPart}/{$file}";
                $filePath = $module ? $module->getExtraPath($filePathPart) : base_path($filePathPart);
                $fileTranslations = include $filePath;
                $trans[$name] = $this->getTranslationsArray($locale, $fileTranslations, $name, $module);
            }
        }

        return $trans;
    }

    private function getTranslationsArray(string $locale, array $arr, string $transKey, $module): array
    {
        $transArr = [];
        foreach ($arr as $key => $value) {
            $newTransKey = "{$transKey}.{$key}";

            if (is_array($value)) {
                $trans = $this->getTranslationsArray($locale, $value, $newTransKey, $module);
            } else {
                $prefix = is_null($module) ? '' : "{$module->getLowerName()}::";
                $trans = __($prefix . $newTransKey, [], $locale);
            }

            $transArr[$key] = $trans;
        }

        return $transArr;
    }

    private function getTranslationsVendor(string $lang): array
    {
        $trans = [];
        $vendorPath = base_path("lang/vendor");

        if (is_dir($vendorPath)) {
            $packages = scandir($vendorPath);
            unset($packages[0]);
            unset($packages[1]);
            foreach ($packages as $dir) {
                $pathFiles = "{$vendorPath}/{$dir}/{$this->getLocaleDefault()}";
                $trans[$dir] = [];
                if (is_dir($pathFiles)) {
                    $files = scandir($pathFiles);
                    unset($files[0]);
                    unset($files[1]);
                    foreach ($files as $file) {
                        $name = explode('.', $file)[0];
                        $filePath = "{$pathFiles}/{$file}";
                        $fileTranslations = include $filePath;
                        $trans[$dir][$name] = $this->getTranslationsArray($lang, $fileTranslations, "{$dir}::{$name}", null);
                    }
                }
            }
        }

        return $trans;
    }

    public function getPageData($page)
    {
        $arr = explode('.', $page);
        $ns = array_shift($arr);
        $key = UtilsCustom::getCamelName(implode('.', $arr));

        $transNsPart = ($ns !== 'app') ? "{$ns}::pages.{$key}" : "pages.{$key}";

        return [
            'breadcrumb' => trans("{$transNsPart}.breadcrumb"),
            'header' => trans("{$transNsPart}.header"),
            'seo' => [
                'title' => trans("{$transNsPart}.seo.title"),
                'description' => trans("{$transNsPart}.seo.description"),
                'keywords' => trans("{$transNsPart}.seo.keywords"),
            ],
        ];
    }
}