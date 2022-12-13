<?php

namespace Omadonex\LaravelTools\Locale\Commands;

use Illuminate\Console\Command;
use Omadonex\LaravelTools\Support\Classes\Utils\UtilsCustom;

class Initialize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'omx:locale:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all data for locale based on config files';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!file_exists(base_path('vendor/umpirsky/country-list'))) {
            $this->error('Error: "umpirsky/country-list" package is not installed! Run: `composer require --dev umpirsky/country-list`');

            return ;
        }

        if (!file_exists(base_path('vendor/umpirsky/currency-list'))) {
            $this->error('Error: "umpirsky/currency-list" package is not installed! Run: `composer require --dev umpirsky/currency-list`');

            return ;
        }

        if (!file_exists(base_path('vendor/umpirsky/language-list'))) {
            $this->error('Error: "umpirsky/language-list" package is not installed! Run: `composer require --dev umpirsky/language-list`');

            return ;
        }

        $langList = config('omx.locale.langList', []);
        $currencyList = config('omx.locale.currencyList', []);

        $path = lang_path('vendor/omx-locale');
        if (file_exists($path)) {
            UtilsCustom::removeDir($path);
        }

        mkdir($path, 0777, true);

        $langWholeList = array_keys(include(base_path("vendor/umpirsky/language-list/data/en/language.php")));
        $countryWholeList = array_keys(include(base_path("vendor/umpirsky/country-list/data/en/country.php")));
        $currencyWholeList = array_keys(include(base_path("vendor/umpirsky/currency-list/data/en/currency.php")));
        file_put_contents(config_path('omx/localeLangList.php'), "<?php return " . var_export($langWholeList, true) . ";");
        file_put_contents(config_path('omx/localeCountryList.php'), "<?php return " . var_export($countryWholeList, true) . ";");
        file_put_contents(config_path('omx/localeCurrencyList.php'), "<?php return " . var_export($currencyWholeList, true) . ";");

        foreach ($langList as $lang) {
            if (!file_exists("{$path}/{$lang}")) {
                mkdir("{$path}/{$lang}");
            }

            $localizedCountryList = array_change_key_case(include(base_path("vendor/umpirsky/country-list/data/{$lang}/country.php")));
            file_put_contents("{$path}/{$lang}/country.php", "<?php return " . var_export($localizedCountryList, true) . ";");

            $localizedCurrencyList = array_change_key_case(include(base_path("vendor/umpirsky/currency-list/data/{$lang}/currency.php")));
            $filteredCurrencyList = array_filter($localizedCurrencyList, function ($key) use ($currencyList) {
                return in_array($key, $currencyList);
            }, ARRAY_FILTER_USE_KEY);
            file_put_contents("{$path}/{$lang}/currency.php", "<?php return " . var_export($filteredCurrencyList, true) . ";");

            $localizedLangList = array_change_key_case(include(base_path("vendor/umpirsky/language-list/data/{$lang}/language.php")));
            $filteredLangList = array_filter($localizedLangList, function ($key) use ($langList) {
                return in_array($key, $langList);
            }, ARRAY_FILTER_USE_KEY);
            file_put_contents("{$path}/{$lang}/lang.php", "<?php return " . var_export($filteredLangList, true) . ";");
        }
    }
}
