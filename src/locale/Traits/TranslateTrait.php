<?php

namespace Omadonex\LaravelTools\Locale\Traits;

use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;

trait TranslateTrait
{
    public function translates()
    {
        return $this->hasMany(get_class() . 'Translate', 'model_id');
    }

    public function getTranslate(string $locale = null, $defaultLangProp = null)
    {
        $locale = $locale ?: app(ILocaleService::class)->getLocaleCurrent();
        $filtered = $this->translates->filter(function ($value, $key) use ($locale) {
            return $value->lang === $locale;
        });

        if ($filtered->count()) {
            return $filtered->first();
        }

        $locale = app(ILocaleService::class)->getLocaleDefault();
        if ($defaultLangProp && property_exists($this, $defaultLangProp)) {
            $locale = $this->$defaultLangProp;
        }

        return $this->translates->filter(function ($value, $key) use ($locale) {
            return $value->lang === $locale;
        })->first();
    }

    public function hasTranslateForLocale(string $locale = null): bool
    {
        $locale = $locale ?: app(ILocaleService::class)->getLocaleCurrent();

        return in_array($locale, $this->translates->pluck('lang')->all());
    }

    public function getAvailableLocaleList(): array
    {
        return app(ILocaleService::class)->getTranslatedLangList($this->translates->pluck('lang')->all());
    }
}
