<?php

namespace Omadonex\LaravelTools\Support\Rules;

use Illuminate\Contracts\Validation\ValidationRule;

class PhoneRule implements ValidationRule
{
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        if (preg_match('/^\+7[0-9]{10}$/', $value) !== 1) {
            $fail('The :attribute must be correct phone.');
        }
    }
}
