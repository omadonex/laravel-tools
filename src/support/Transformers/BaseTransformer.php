<?php

namespace Omadonex\LaravelTools\Support\Transformers;

abstract class BaseTransformer
{
    protected $response;
    protected $originalData;
    protected $convertTranslate;

    public function __construct($response, $convertTranslate = true)
    {
        $this->response = $response;
        $this->convertTranslate = $convertTranslate;
        $this->originalData = $this->response->data;
        $this->columns = count($this->response->data) ? array_keys((array)$this->response->data[0]) : [];
    }

    protected abstract function transformers();

    protected function makeBooleanIcon()
    {
        return function ($value, $row) {
            return $value ? boolIcon() : '';
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
