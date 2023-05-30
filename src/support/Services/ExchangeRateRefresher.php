<?php

namespace Omadonex\LaravelTools\Support\Services;

use Illuminate\Support\Facades\Http;

class ExchangeRateRefresher extends OmxService
{
    // зеркало ЦРБ. Так как сам ЦРБ отвечает 1 раз через десять.  https://www.cbr-xml-daily.ru/
    protected string $serviceUrl = 'https://www.cbr-xml-daily.ru/daily.xml';

    protected function loadCurrencies()
    {
        try {
            $data = simplexml_load_string(Http::retry(3, 500)->get($this->serviceUrl)->body());
        } catch (\Exception $e) {
            $data = null;
        }

        return $data;
    }

    /**
     * Получаем значение текущего курса валюты
     *
     * @param string $currency Строковое наименование валюты
     *
     * @return float
     * @throws \Exception
     */
    public function getCurrencyExchange($currency)
    {
        $course     = 0;
        $currencies = $this->loadCurrencies();

        if (is_null($currencies)) {
            throw new \Exception('Fail to load currency');
        }

        foreach ($currencies as $item) {
            if (strtoupper($item->CharCode) === strtoupper($currency)) {
                $course = $item->Value;
                break;
            }
        }

        return (float) str_replace(',', '.', $course);
    }
}