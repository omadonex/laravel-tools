<?php

namespace Omadonex\LaravelTools\Acl\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Omadonex\LaravelTools\Acl\Models\User;

class UserUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255',
                Rule::unique(User::class)->ignore($this->route()->user),
            ],
            'email' => ['required', 'string', 'email', 'max:255',
                Rule::unique(User::class)->ignore($this->route()->user),
            ],
            'phone' => ['nullable', 'max:10'],
            'display_name' => ['nullable'],
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'opt_name' => ['nullable'],
        ];
    }
}
