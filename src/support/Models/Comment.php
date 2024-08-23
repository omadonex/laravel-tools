<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Omadonex\LaravelTools\Acl\Models\User;

/**
 * Модель Comment.
 * Комментарии
 *
 * @property int         $id               Первичный ключ
 * @property string      $commentable_type Тип сущности
 * @property int         $commentable_id   ID сущности
 * @property string      $text             Комментарий
 * @property int         $user_id          ID пользователя
 * @property Carbon|null $created_at       Дата создания
 * @property Carbon|null $updated_at       Дата обновления
 *
 */
class Comment extends Model
{
    protected $guarded = [ 'id' ];
    protected $table   = 'support_comment';
    protected $fillable = [
        'commentable_type',
        'commentable_id',
        'text',
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
