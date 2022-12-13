<?php

namespace Omadonex\LaravelTools\Acl\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Locale\Traits\TranslateResourceTrait;

class RoleResource extends JsonResource
{
    use TranslateResourceTrait;

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return array_merge([
            'id' => $this->id,
            'isRoot' => $this->is_root,
            'isStaff' => $this->is_staff,
        ], $this->getTranslateIfLoaded(RoleTranslateResource::class, false));
    }
}
