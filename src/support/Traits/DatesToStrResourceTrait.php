<?php

namespace Omadonex\LaravelTools\Support\Traits;

trait DatesToStrResourceTrait
{
    public function datesToStrResource($propNames)
    {
        $data = [];
        foreach ($propNames as $propName) {
            $data[$propName] = [
                'diff' => $this->$propName ? $this->$propName->diffForHumans() : null,
            ];
        }

        return $data;
    }
}