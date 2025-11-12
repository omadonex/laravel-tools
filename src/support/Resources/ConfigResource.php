<?php

namespace Omadonex\LaravelTools\Support\Resources;

use Omadonex\LaravelTools\Support\Models\Config;

class ConfigResource extends TypedKeyValueDictionaryResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var Config $this */

        return [

        ] + parent::toArray($request);
    }
}
