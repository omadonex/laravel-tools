<?php

use Omadonex\LaravelTools\Support\Models\HistoryEvent;

return [
    HistoryEvent::CREATE => [
        'name' => 'Запись создана',
    ],

    HistoryEvent::CREATE_T => [
        'name' => 'Перевод создан',
    ],

    HistoryEvent::UPDATE => [
        'name' => 'Запись обновлена',
    ],

    HistoryEvent::UPDATE_T => [
        'name' => 'Перевод обновлен',
    ],

    HistoryEvent::DELETE => [
        'name' => 'Запись удалена',
    ],

    HistoryEvent::DELETE_T => [
        'name' => 'Перевод удален',
    ],

    HistoryEvent::DELETE_T_ALL => [
        'name' => 'Все переводы удалены',
    ],
];