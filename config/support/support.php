<?php

return [
    'configPath' => 'admin.setting.config',

    'stubs' => [
        'class' => [
            'controller' => 'App\\Http\\Controllers',
            'controllerWithHistory' => 'App\\Http\\Controllers',
            'history' => 'App\\Models',
            'model' => 'App\\Models',
            'modelWithHistory' => 'App\\Models',
            'modelView' => 'App\\ModelView',
            'repository' => 'App\\Repositories\\Model',
            'request' => 'App\\Http\\Requests',
            'resource' => 'App\\Resources\\Default',
            'resourceDatatables' => 'App\\Resources\\Datatables',
            'service' => 'App\\Services\\Model',
            'transformer' => 'App\\Transformers',
        ],
        'view' => [
            'form' => 'resources/views',
        ],
        'asset' => [
            'index' => 'resources/assets/ts/pages',
            'show' => 'resources/assets/ts/pages',
            'history' => 'resources/assets/ts/pages',
        ],
    ],
];