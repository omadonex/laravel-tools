<?php

namespace Omadonex\LaravelTools\Acl\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class RoleAttachRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'role_id' => ['required'],
        ];
    }
}
