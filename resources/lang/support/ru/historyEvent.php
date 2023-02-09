<?php

use Omadonex\LaravelTools\Support\Models\HistoryEvent;

return [
    HistoryEvent::CREATE => [
        'name' => 'Запись создана',
    ],

    HistoryEvent::UPDATE => [
        'name' => 'Запись обновлена',
    ],

    HistoryEvent::DELETE => [
        'name' => 'Запись удалена',
    ],
];