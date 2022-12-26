<?php

return [
    ['name' => 'item 1', 'route' => '#', 'permission' => 'access prm 1', 'sub' => [
        ['name' => 'sub item 1', 'route' => 'my.route.name.1', 'permission' => 'access prm 1-1'],
        ['name' => 'sub item 2', 'route' => 'my.route.name.2', 'permission' => 'access prm 1-2'],
        ['name' => 'sub item 3', 'route' => 'url', 'static' => true, 'permission' => 'access prm 1-3'],
//            ...
    ]],
    ['name' => '', 'line' => true],
    ['name' => 'item 2 without subitems', 'route' => 'my.route.name', 'permission' => 'access prm 2'],
    ['name' => 'item 3', 'route' => '#', 'permission' => 'access prm 3', 'sub' => [
//            ...
    ]],
    ['name' => 'translate key string', 't' => true, 'route' => '#', 'permission' => 'access prm 4', 'sub' => [
//            ...
    ]],
    ['name' => 'no permission item', 'route' => '#', 'sub' => [
//            ...
    ]],
];
