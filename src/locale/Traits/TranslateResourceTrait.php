<?php

namespace Omadonex\LaravelTools\Locale\Traits;

use Omadonex\LaravelTools\Locale\Interfaces\ILocaleService;

trait TranslateResourceTrait
{
    public function getTranslateIfLoaded($translateResourceClass, $full = true, $locale = null)
    {
        $locale = $locale ?: app(ILocaleService::class)->getLocaleCurrent();
        $data = [];

        if ($this->resource->relationLoaded('translates')) {
            $data['t'] = new $translateResourceClass($this->getTranslate($locale, ILocaleService::PROP_LOCALE_DEFAULT));
            if ($full) {
                $data['t_has'] = $this->hasTranslateForLang();
                $data['t_list'] = $this->getAvailableLangList();
            }
        }

        return $data;
    }
}
