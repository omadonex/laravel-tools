<?php

namespace Omadonex\LaravelTools\Support\Classes\Dto;

class DtoUserNames
{
    public ?string $fname;
    public ?string $lname;
    public ?string $oname;
    public ?string $display;
    public ?string $username;

    public function __construct(?string $fname, ?string $lname, ?string $oname, ?string $display, ?string $username)
    {
        $this->fname = $fname;
        $this->lname = $lname;
        $this->oname = $oname;
        $this->display = $display;
        $this->username = $username;
    }

    public static function parseFromResource($resource, string $userColumn): DtoUserNames
    {
        $fnameField = "{$userColumn}_u_fname";
        $lnameField = "{$userColumn}_u_lname";
        $onameField = "{$userColumn}_u_oname";
        $displayField = "{$userColumn}_u_display";
        $usernameField = "{$userColumn}_u_username";

        return new DtoUserNames(
            $resource->$fnameField,
            $resource->$lnameField,
            $resource->$onameField,
            $resource->$displayField,
            $resource->$usernameField
        );
    }

    public static function parseFromUser($user): DtoUserNames
    {
        return new DtoUserNames(
            $user->meta->first_name,
            $user->meta->last_name,
            $user->meta->opt_name,
            $user->meta->display_name,
            $user->username,
        );
    }
}