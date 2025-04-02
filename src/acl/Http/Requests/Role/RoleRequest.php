<?php

namespace Omadonex\LaravelTools\Acl\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;

class RoleRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => ['required'],
            'description' => ['required'],
            'is_staff' => ['required'],
            'is_hidden' => ['required'],
        ];
    }
}
