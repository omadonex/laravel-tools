<?php

namespace Omadonex\LaravelTools\Support\Classes\Tools;

use Omadonex\LaravelTools\Support\Classes\Dto\DtoUserNames;
use Ramsey\Uuid\Uuid;

class ToolsPersonNames
{
    protected DtoUserNames $data;

    public function __construct(DtoUserNames $data)
    {
        $this->data = $data;
    }

//    public function getDefaultName(): string
//    {
//        return __('support::common.user');
//    }

    public function getUsername(): string
    {
//        if (Uuid::isValid($this->data->username)) {
//            return self::getDefaultName();
//        }

        return $this->data->username;
    }

    public function getDisplayName(): string
    {
        if ($this->data->display) {
            return $this->data->display;
        }

        return $this->getUsername();
    }

    /**
     * Полное обращение к человеку (Фамилия Имя Отчество)
     */
    public function getFullName(bool $strict = false): string
    {
        return trim($this->data->lname . ' ' . $this->data->fname . ' ' . $this->data->oname) ?: (($strict) ? '' : $this->getDisplayName());
    }

    /**
     * Короткое обращение к человеку (Имя и Фамилия)
     */
    public function getShortNameAttribute(bool $strict = false): string
    {
        return trim($this->data->fname . ' ' . $this->data->lname) ?: (($strict) ? '' : $this->getDisplayName());
    }

    /**
     * Официальное обращение к человеку (Имя и Отчество)
     */
    public function getOfficialName(bool $strict = false): string
    {
        return trim($this->data->fname . ' ' . $this->data->oname) ?: (($strict) ? '' : $this->getDisplayName());
    }

    /**
     * Фамилия, инициалы
     */
    public function getInitialsName(bool $strict = false): string
    {
        $initials = '';
        if ($this->data->fname) {
            $initials .= mb_substr($this->data->fname, 0, 1) . '.';
        }
        if ($this->data->oname) {
            $initials .= mb_substr($this->data->oname, 0, 1) . '.';
        }

        return trim($this->data->lname . ' ' . $initials) ?: (($strict) ? '' : $this->getDisplayName());
    }
}