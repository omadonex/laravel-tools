<?php

namespace Omadonex\LaravelTools\Support\Traits;

use Ramsey\Uuid\Uuid;

trait PersonNamesTrait
{
    /**
     * Имя по умолчанию (если ничего больше не указано)
     * @return string
     */
    protected function getDefaultNameAttribute(): string
    {
        return trans('support::common.user');
    }

    /**
     * Имя пользователя (username)
     * @param string $value
     * @return string
     */
    public function getUsernameAttribute(string $value): string
    {
        return Uuid::isValid($value) ? $this->defaultName : $value;
    }

    /**
     * Отображаемое имя (как указал пользователь)
     * @return string
     */
    public function getDisplayNameAttribute($value): string
    {
        return $value ?: $this->username;
    }

    /**
     * Полное обращение к человеку (Фамилия Имя Отчество)
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return trim($this->last_name . ' ' . $this->first_name . ' ' . $this->opt_name) ?: '';
    }

    /**
     * Короткое обращение к человеку (Имя и Фамилия)
     * @return string
     */
    public function getShortNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->last_name) ?: '';
    }

    /**
     * Официальное обращение к человеку (Имя и Отчество)
     * @return string
     */
    public function getOfficialNameAttribute(): string
    {
        return trim($this->first_name . ' ' . $this->opt_name) ?: '';
    }

    /**
     * Фамилия, инициалы
     * @return string
     */
    public function getInitialsNameAttribute(): string
    {
        $initials = '';
        if ($this->first_name) {
            $initials .= mb_substr($this->first_name, 0, 1) . '.';
        }
        if ($this->opt_name) {
            $initials .= mb_substr($this->opt_name, 0, 1) . '.';
        }

        return trim($this->last_name . ' ' . $initials) ?: '';
    }
}
