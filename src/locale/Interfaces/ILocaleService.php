<?php

namespace Omadonex\LaravelTools\Locale\Interfaces;

interface ILocaleService
{
    const PROP_LOCALE_DEFAULT = 'localeDefault';
    const ENTRY_AUTH = 'auth';
    const ENTRY_ALL = 'all';

    /**
     * Returns default language key
     */
    public function getLocaleDefault(): string;

    /**
     * Returns current language key
     */
    public function getLocaleCurrent(): string;

    /**
     * Returns default currency key
     */
    public function getCurrencyDefault(): string;

    /**
     * Returns language list translated in specific locale
     */
    public function getTranslatedLangList(array $langList = [], string $locale = null, bool $addNative = true): array;

    /**
     * Returns currency list translated in specific locale
     */
    public function getTranslatedCurrencyList(array $currencyList = [], string $locale = null): array;

    /**
     * Returns country list translated in specific locale
     */
    public function getTranslatedCountryList(array $countryList = [], string $locale = null): array;

    /**
     * Check that locale abbreviation is correct
     */
    public function isLocaleCorrect(string $locale): bool;

    /**
     * Check that country abbreviation is correct
     */
    public function isCountryCorrect(string $country): bool;

    /**
     * Check that currency abbreviation is correct
     */
    public function isCurrencyCorrect(string $currency): bool;

    /**
     * Check that locale is supported
     */
    public function isLocaleSupported(string $locale): bool;

    /**
     * Determine current locale based on a first segment of route
     */
    public function setLocaleFromRoute(): ?string;

    /**
     * Returns url without locale segment
     */
    public function getUrlWithoutLocale(string $url): string;

    /**
     * Generates array of links for url with locale prefix from array of supported locales
     */
    public function getRouteLangList(string $url): array;

    /**
     * Generates translation array that consists of all localization files
     */
    public function getTotalData(string $locale = null): array;
}