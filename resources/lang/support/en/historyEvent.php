<?php

use Omadonex\LaravelTools\Support\Models\HistoryEvent;

return [
    HistoryEvent::CREATE => [
        'name' => 'Record created',
    ],

    HistoryEvent::UPDATE => [
        'name' => 'Record updated',
    ],

    HistoryEvent::DELETE => [
        'name' => 'Record deleted',
    ],
];