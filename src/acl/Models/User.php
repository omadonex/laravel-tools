<?php

namespace Omadonex\LaravelTools\Acl\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Omadonex\LaravelTools\Acl\Traits\AclTrait;
use Omadonex\LaravelTools\Common\Tools\Avatar;
use Omadonex\LaravelTools\Support\Traits\PersonNamesTrait;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    use AclTrait;
    use SoftDeletes;
    use PersonNamesTrait;

    public const MODEL_SHOW_URL = 'admin.acl.user.show';
    public const HISTORY_ENABLED = true;

    protected $fillable = [
        'username',
        'email',
        'first_name',
        'last_name',
        'opt_name',
        'display_name',
        'phone_code',
        'phone',
        'password',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'phone_verified_at' => 'datetime',
        ];
    }

    public function getAvatar(): string
    {
        return Avatar::get($this->avatar);
    }

    public static function getPath(): string
    {
        return config('omx.acl.acl.userPath');
    }

    public static function getFormPath(): string
    {
        return 'omx-bootstrap::pages.user';
    }

    public static function getRouteName(string $resourcePart): string
    {
        return self::getPath() . ".{$resourcePart}";
    }
}
