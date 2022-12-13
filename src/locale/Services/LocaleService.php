<?php

namespace Omadonex\LaravelTools\Locale\Services;

use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class LocaleService implements ILocaleService
{
    private $moduleList;
    private $langList;
    private $langAllList;
    private $currencyList;

    protected $entryMap = [];

    /**
     * Locale constructor.
     * @param array $moduleList
     */
    public function __construct(array $moduleList = [])
    {
        $this->moduleList = $moduleList;

        $this->langList = $this->getLangSupportedList();
        $this->langAllList = config('omx.lang');
        $this->currencyList = $this->getCurrencySupportedList();
    }

    public function getLangDefault(): string
    {
        return config('app.fallback_locale');
    }

    public function getLangCurrent(): string
    {
        return app()->getLocale();
    }

    public function getCurrencyDefault(): string
    {
        return config('omx.locale.currencyDefault');
    }

    protected function getLangSupportedList(): array
    {
        return config('omx.locale.langList');
    }

    protected function getCurrencySupportedList(): array
    {
        return config('omx.locale.currencyList');
    }

    private function getLangFact(string $lang = null): string
    {
        if (($lang === null) || !$this->isLangSupported($lang)) {
            return $this->getLangDefault();
        }

        return $lang;
    }

    /**
     * @param array $langList
     * @param string|null $langTrans
     * @param bool $addNative
     *
     * @return array
     */
    public function getLangList(array $langList = [], string $locale = null, bool $addNative = true): array
    {
        $langList = $langList ? array_intersect($langList, $this->langList) : $this->langList;
        $locale = $this->getLangFact($locale);

        $list = [];
        foreach ($langList as $lang) {
            $item = [
                'lang' => $lang,
                'name' => config("omx.locale.{$langTrans}.lang")[$lang],
            ];

            if ($addNative) {
                $item['native'] = config("omx.locale.{$lang}.lang")[$lang];
            }

            $list[] = $item;
        }

        return $list;
    }

    /**
     * @param array $currencyList
     * @param string|null $langTrans
     *
     * @return array
     */
    public function getCurrencyList(array $currencyList = [], string $langTrans = null): array
    {
        $currencyList = $currencyList ? array_intersect($currencyList, $this->currencyList) : $this->currencyList;
        $langTrans = $this->getLangFact($langTrans);

        $list = [];
        foreach ($currencyList as $currency) {
            $list[] = [
                'currency' => $currency,
                'name' => config("omx.locale.{$langTrans}.currency")[$currency],
            ];
        }

        return $list;
    }

    /**
     * @param string|null $langTrans
     *
     * @return array
     */
    public function getCountryList(string $langTrans = null): array
    {
        $langTrans = $this->getLangFact($langTrans);

        return config("omx.locale.{$langTrans}.country");
    }

    /**
     * @param string|null $lang
     */
    public function setLang(string $lang = null): void
    {
        $lang = $this->getLangFact($lang);
        if ($lang !== $this->getLangCurrent()) {
            $this->app->setLocale($lang);
            //Carbon::setLocale($language);
        }
    }

    /**
     * @return string|null
     */
    public function setLangFromRoute(): ?string
    {
        $lang = $this->app->request->segment(1);
        if ($lang && $this->isLangCorrect($lang)) {
            $lang = $this->isLangSupported($lang) ? $lang : null;
            $this->setLang($lang);

            return $lang;
        }

        $this->setLang();

        return null;
    }

    /**
     * @param string $lang
     * @return bool
     */
    public function isLangCorrect(string $lang): bool
    {
        return in_array($lang, $this->langAllList);
    }

    /**
     * @param string $lang
     * @return bool
     */
    public function isLangSupported(string $lang): bool
    {
        return in_array($lang, $this->langList);
    }

    /**
     * @return array
     */
    public function getLangAllList(): array
    {
        return $this->langAllList;
    }

    /**
     * @param string $url
     * @return string
     */
    public function getUrlWithoutLang(string $url): string
    {
        $parsed = parse_url($url);
        if (!array_key_exists('path', $parsed)) {
            return $url;
        }

        $segments = explode('/', $parsed['path']);
        $lang = $segments[1];

        if ($this->isLangCorrect($lang)) {
            if (count($segments) > 2) {
                return preg_replace("/\/{$lang}\//", '/', $url, 1);
            }

            return preg_replace("/\/{$lang}/", '', $url, 1);
        }

        return $url;
    }

    /**
     * @param string $currentUrl
     * @return array
     */
    public function getRouteLangList(string $currentUrl): array
    {
        $currentUrlWithoutLang = $this->getUrlWithoutLang($currentUrl);
        $parsed = parse_url($currentUrlWithoutLang);
        $path = $parsed['path'] ?? '';

        $list = [];
        foreach ($this->getLangList() as $langItem) {
            if ($langItem['lang'] !== $this->getLangDefault()) {
                $parsed['path'] = "/{$langItem['lang']}{$path}";
            } else {
                $parsed['path'] = $path;
            }

            $list[] = [
                'lang' => $langItem['lang'],
                'name' => $langItem['native'],
                'url' => UtilsCustom::buildUrl($parsed),
                'flag' => $this->getFlag($langItem['lang']),
            ];
        }

        return $list;
    }

    /**
     * @param string $name
     * @param $parameters
     * @param bool $absolute
     *
     * @return string
     */
    public function route(string $name, $parameters = [], bool $absolute = true): string
    {
        $url = route($name, $parameters, $absolute);

        $lang = $this->getLangCurrent();
        if ($lang === $this->getLangDefault()) {
            return $url;
        }

        $parsed = parse_url($url);
        $parsed['path'] = "/{$lang}{$parsed['path']}";

        return UtilsCustom::buildUrl($parsed);
    }

    /**
     * @param string $lang
     *
     * @return string
     */
    public function getFlag(string $lang): string
    {
        switch ($lang) {
            case 'en': return 'us';
        }

        return $lang;
    }

    /**
     * @return string
     */
    public function getFlagCurrent(): string
    {
        return $this->getFlag($this->getLangCurrent());
    }

    /**
     * @param string $entry
     *
     * @return array
     */
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

    /**
     * @param string|null $lang
     * @param string $entry
     *
     * @return array
     */
    public function getEntryData(string $lang = null, string $entry = self::ENTRY_ALL): array
    {
        $totalData = $this->getTotalData($lang);
        $entryList = $this->getEntryMap($entry);

//        if (count($entryModuleList)) {
//            if ($entryModuleList[0] === '*') {
//                $entryModuleList = array_keys($this->moduleList);
//            } elseif ($entryModuleList[0] === '^') {
//                $entryModuleList = array_diff(array_keys($this->moduleList), array_slice($entryModuleList, 0));
//            }
//        }
//

    }

    /**
     * @param string|null $lang
     *
     * @return array
     */
    public function getTotalData(string $lang = null): array
    {
        $data = [];

        $langList = $lang ? [$lang] : $this->getLangSupportedList();
        foreach ($langList as $langItem) {
            $data[$langItem]['app'] = $this->getTranslations($langItem);
            $data[$langItem]['vendor'] = $this->getTranslationsVendor($langItem);
            foreach ($this->moduleList as $module) {
                $trans = $this->getTranslations($langItem, $module);
                if (!is_null($trans)) {
                    $data[$langItem][$module->getLowerName()] = $trans;
                }
            }
        }

        return $data;
    }

    /**
     * @param string $lang
     * @param null $module
     *
     * @return array
     */
    private function getTranslations(string $lang, $module = null): array
    {
        $trans = [];
        if (is_null($module)) {
            $pathPart = "lang/{$this->getLangDefault()}";
            $path = base_path($pathPart);
        } else {
            $pathPart = "Resources/lang/{$this->getLangDefault()}";
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
                $trans[$name] = $this->getTranslationsArray($lang, $fileTranslations, $name, $module);
            }
        }

        return $trans;
    }

    /**
     * @param string $lang
     * @param array $arr
     * @param string $transKey
     * @param $module
     *
     * @return array
     */
    private function getTranslationsArray(string $lang, array $arr, string $transKey, $module): array
    {
        $transArr = [];
        foreach ($arr as $key => $value) {
            $newTransKey = "{$transKey}.{$key}";

            if (is_array($value)) {
                $trans = $this->getTranslationsArray($lang, $value, $newTransKey, $module);
            } else {
                $prefix = is_null($module) ? '' : "{$module->getLowerName()}::";
                $trans = trans($prefix . $newTransKey, [], $lang);
            }

            $transArr[$key] = $trans;
        }

        return $transArr;
    }

    /**
     * @param string $lang
     *
     * @return array
     */
    private function getTranslationsVendor(string $lang): array
    {
        $trans = [];
        $vendorPath = base_path("lang/vendor");

        if (is_dir($vendorPath)) {
            $packages = scandir($vendorPath);
            unset($packages[0]);
            unset($packages[1]);
            foreach ($packages as $dir) {
                $pathFiles = "{$vendorPath}/{$dir}/{$this->getLangDefault()}";
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