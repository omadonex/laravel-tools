<?php

namespace Omadonex\LaravelTools\Support\ReadyCRUDPages\ConfigPage\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Omadonex\LaravelTools\Acl\Models\User;
use Omadonex\LaravelTools\Support\Models\Eloquent;
use Omadonex\LaravelTools\Support\Models\History;

/**
 * Модель ManufacturerHistory.
 * История
 *
 * @property int $id                    Первичный ключ
 * @property int $model_id              ID производителя
 * @property int $user_id               ID пользователя
 * @property int $history_event_id      ID события
 * @property object $data               Старое и новое значение
 * @property Carbon|null $occurred_at   Дата записи
 *
 *
 * @mixin Eloquent
 */
class ConfigHistory extends History
{
    protected $table   = 'support_config_history';

    // RELATIONS

    public function config(): BelongsTo
    {
        return $this->belongsTo(Config::class, 'id', 'model_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
