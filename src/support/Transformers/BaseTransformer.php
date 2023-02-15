<?php

namespace Omadonex\LaravelTools\Support\Transformers;

use Carbon\Carbon;

abstract class BaseTransformer
{
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

    protected function makeDateTime($format = 'd.m.Y H:i:s')
    {
        return function ($value, $row) use ($format) {
            return Carbon::parse($value)->format($format);
        };
    }

    protected function makeLink($urlName)
    {
        return function ($value, $row) use ($urlName) {
            $url = route($urlName, $value);

            return "<a href=\"{$url}\">{$value}</a>";
        };
    }

    protected function makePrice()
    {
        return function ($value, $row) {
            return number_format((float)$value, 2, ',', ' ');
        };
    }

    protected function makePercent()
    {
        return function ($value, $row) {
            return "{$value} %";
        };
    }

    protected function makeIfEmpty($urlName)
    {
        return function ($value, $row) use ($urlName) {
            return empty($value) ? '&mdash;' : $value;
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
