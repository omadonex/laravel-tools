<?php

namespace Omadonex\LaravelTools\Acl\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleTranslateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'lang' => $this->lang,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
