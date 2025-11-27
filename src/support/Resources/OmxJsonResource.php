<?php

namespace Omadonex\LaravelTools\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Support\Traits\ConvertCheckboxValuesTrait;

class OmxJsonResource extends JsonResource
{
    use ConvertCheckboxValuesTrait;

    public function jstData(): array
    {
        return [
            'jstReadonlyFields' => $this->getJstReadonlyFieldsAttribute(),
            'jstHiddenFields' => $this->getJstHiddenFieldsAttribute(),
            'jstDisabledFields' => $this->getJstDisabledFieldsAttribute(),
        ];
    }

    public function convertedToCheckboxValues(array $attributes): array
    {
        $data = [];
        foreach ($attributes as $attribute) {
            $data["{$attribute}_checkbox"] = $this->convertToCheckboxValue($this->$attribute);
        }

        return $data;
    }
}

