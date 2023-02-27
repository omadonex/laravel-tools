<?php

use Omadonex\LaravelTools\Support\Models\HistoryEvent;

return [
    HistoryEvent::CREATE => [
        'name' => 'Запись создана',
    ],

    HistoryEvent::CREATE_WITH_T => [
        'name' => 'Запись создана + перевод',
    ],

    HistoryEvent::CREATE_T => [
        'name' => 'Перевод создан',
    ],

    HistoryEvent::UPDATE => [
        'name' => 'Запись обновлена',
    ],

    HistoryEvent::DELETE => [
        'name' => 'Запись удалена',
    ],
];