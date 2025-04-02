<?php

namespace Omadonex\LaravelTools\Acl\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserDatatablesResource extends JsonResource
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
            'username' => $this->username,
            'email' => $this->email,
            'phone_code' => $this->phone_code,
            'phone' => $this->phone,
            'display_name' => $this->display_name,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'opt_name' => $this->opt_name,
            'avatar' => $this->avatar,
            'roles_ids' => $this->roles_ids,
            'roles_ids_label' => $this->roles_ids_label,
        ];
    }
}
