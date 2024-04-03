<?php

namespace Omadonex\LaravelTools\Acl\Http\Controllers;

use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;

class AclController extends Controller
{
    public function route(IAclService $aclService)
    {
        return view('omx-acl::route', ['routesData' => $aclService->getRoutesData()]);
    }

    public function table(IAclService $aclService)
    {

    }
}
