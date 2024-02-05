<?php

namespace Omadonex\LaravelTools\Support\Transformers;

use App\Tools\Caption;
use Carbon\Carbon;
use Omadonex\LaravelTools\Locale\Classes\Utils\UtilsCurrencySign;

abstract class BaseTransformer
{
    const CROPPED_STRING_LENGTH = 175;
    protected $params;
    protected $response;
    protected $originalData;
    protected $convertTranslate;

    public function __construct($response, $params = [], $convertTranslate = true)
    {
        $this->params = $params;
        $this->response = $response;
        $this->convertTranslate = $convertTranslate;
        $this->originalData = $this->response->data;
        $this->columns = count($this->response->data) ? array_keys((array)$this->response->data[0]) : [];
    }

    protected abstract function transformers();

    protected function makeBooleanIcon()
    {
        return function ($value, $row) {
            return boolIcon($value);
        };
    }

    protected function makeDateTime($format = 'd.m.Y H:i:s', $timezone = 'Europe/Moscow')
    {
        return function ($value, $row) use ($format, $timezone) {
            return Carbon::parse($value)->timezone($timezone)->format($format);
        };
    }

    protected function makeLink(string $urlName, string $caption = null, string $keyName = null, int $croppedLength = null)
    {
        return function ($value, $row) use ($urlName, $caption, $keyName, $croppedLength) {
            $id = $keyName ? $row->$keyName : $value;
            if (!$id) {
                return $this->makeIfEmpty()($value, $row);
            }

            $url = route($urlName, $id);
            $text = $caption ?: $value;

            if (!$croppedLength || mb_strlen($text) <= $croppedLength) {
                $croppedText = $text;
            } else {
                $croppedText = mb_substr($text, 0, $croppedLength) . '...';
            }

            return "<a href=\"{$url}\" title='{$text}'>{$croppedText}</a>";
        };
    }

    protected function makeMoney(bool|string $useCurrency = true, bool $useEmptyCaption = false, string $currencyColumn = 'currency', int $digits = 2)
    {
        return function ($value, $row) use ($useCurrency, $useEmptyCaption, $currencyColumn, $digits) {
            if ($useEmptyCaption && $value === null) {
                return Caption::EMPTY;
            }

            $str = number_format((float)$value, $digits, ',', ' ');
            if ($useCurrency) {
                $str .= ' ' . UtilsCurrencySign::get($useCurrency === true ? $row->$currencyColumn : $useCurrency);
            }

            return $str;
        };
    }

    protected function makeFloat(int $digits = 2)
    {
        return function ($value, $row) use ($digits) {
            return number_format((float)$value, $digits, ',', ' ');
        };
    }

    protected function makePercent()
    {
        return function ($value, $row) {
            return "{$value} %";
        };
    }

    protected function makeIfEmpty()
    {
        return function ($value, $row) {
            return empty($value) ? '&mdash;' : $value;
        };
    }

    protected function makeCropped($croppedLength = self::CROPPED_STRING_LENGTH)
    {
        return function ($value, $row) use ($croppedLength) {
            $croppedText = (mb_strlen($value) <= $croppedLength) ? $value : (mb_substr($value, 0, $croppedLength) . '...');

            return "<span title='{$value}'>{$croppedText}</span>";
        };
    }


    protected function setFromAnother(string $column)
    {
        return function ($value, $row) use ($column) {
            return $row[$column];
        };
    }

    public function getTransformedData()
    {
        $transformers = $this->transformers();
        $transformedData = [];
        foreach ($this->response->data as $item) {
            $transformedData[] = $this->applyTransformers($item, $transformers);
        }

        return $transformedData;
    }

    private function applyTransformers($row, $transformers)
    {
        foreach ($transformers as $column => $transformer) {
            $row->$column = $transformer(in_array($column, $this->columns) ? $row->$column : null, $row);
        }

        if ($this->convertTranslate && property_exists($row, 't')) {
            foreach ($row->t as $key => $value) {
                $tCol = "t_{$key}";
                $row->$tCol = $value;
            }
        }

        return $row;
    }

    public function getTransformedResponse()
    {
        $this->response->data = $this->getTransformedData();

        return $this->response;
    }
}
