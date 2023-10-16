<?php

namespace Omadonex\LaravelTools\Support\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * Модель ColumnSet.
 * Настройки отображения таблиц
 *
 * @property int         $id          Первичный ключ
 * @property string      $name        Наименование
 * @property string      $table_id    ID таблицы
 * @property array       $columns     Колонки
 * @property Carbon|null $created_at  Дата создания
 * @property Carbon|null $updated_at  Дата обновления
 *
 */
class ColumnSet extends Model
{
    protected $guarded = [ 'id' ];
    protected $table   = 'support_column_set';
    protected $casts   = [
        'columns' => 'array',
    ];
}
