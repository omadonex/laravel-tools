<?php

namespace Omadonex\LaravelTools\Acl\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Acl\Models\User;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var User $this */

        return [
            'id' => $this->id,
            'username' => $this->username,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'phone' => $this->phone,
            'phone_verified_at' => $this->phone_verified_at,
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'opt_name' => $this->opt_name,
            'avatar' => $this->avatar,
        ];
    }
}
