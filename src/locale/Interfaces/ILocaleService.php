<?php

namespace Omadonex\LaravelTools\Locale\Interfaces;

interface ILocaleService
{
    const PROP_LANG_DEFAULT = 'langDefault';

    const ENTRY_AUTH = 'auth';
    const ENTRY_ALL = 'all';

    /**
     * Returns default language key
     */
    public function getLangDefault(): string;

    /**
     * Returns current language key
     */
    public function getLangCurrent(): string;

    /**
     * Returns default currency key
     */
    public function getCurrencyDefault(): string;

    /**
     * Returns language list
     */
    public function getLangList(array $langList = [], string $langTrans = null, bool $addNative = true): array;

    /**
     * Returns currency list
     */
    public function getCurrencyList(array $currencyList = [], string $langTrans = null): array;

    /**
     * Returns country list
     */
    public function getCountryList(string $langTrans = null): array;
}