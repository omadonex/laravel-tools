<?php

namespace Omadonex\LaravelTools\Acl\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleDatatablesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_hidden' => $this->is_hidden,
            'is_staff' => $this->is_staff,
        ];
    }
}
