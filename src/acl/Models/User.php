<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Omadonex\LaravelTools\Acl\Traits\AclTrait;
use Omadonex\LaravelTools\Common\Tools\Avatar;
use Omadonex\LaravelTools\Support\Traits\PersonNamesTrait;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, AclTrait;
    use SoftDeletes;
    use PersonNamesTrait;

    protected $fillable = [
        'username',
        'email',
        'phone',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public const MODEL_SHOW_URL = 'admin.acl.user.show';

    public bool $historyEnabled = true;

    public function getAvatar(): string
    {
        return Avatar::get($this->avatar);
    }
}
