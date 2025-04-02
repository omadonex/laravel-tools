<?php

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;

return [
    'mode' => IAclService::MODE_DENY,
    'deepMode' => true,
    'rolePath' => 'admin.acl.role',
    'userPath' => 'admin.acl.user',
];
