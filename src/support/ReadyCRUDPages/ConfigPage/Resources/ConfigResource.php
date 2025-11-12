<?php

namespace Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Resources;

use Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Models\Config;
use Omadonex\LaravelTools\Support\Resources\TypedKeyValueDictionaryResource;

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
