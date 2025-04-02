<?php

namespace Omadonex\LaravelTools\Acl\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;
use Omadonex\LaravelTools\Acl\Models\User;

class UserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'phone' => ['nullable', 'max:10'],
            'display_name' => ['nullable'],
            'first_name' => ['nullable'],
            'last_name' => ['nullable'],
            'opt_name' => ['nullable'],
        ];
    }
}
