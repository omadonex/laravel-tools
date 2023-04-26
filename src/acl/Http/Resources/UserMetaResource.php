<?php

namespace Omadonex\LaravelTools\Acl\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Acl\Models\UserMeta;

class UserMetaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var UserMeta $this */

        return [
            'user_id' => $this->user_id,
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'opt_name' => $this->opt_name,
            'avatar' => $this->avatar,

            'user' => new UserResource($this->whenLoaded('user')),
        ];
    }
}
