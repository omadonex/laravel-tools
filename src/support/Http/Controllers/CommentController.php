<?php

namespace Omadonex\LaravelTools\Support\Http\Controllers;


use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Http\Requests\CommentRequest;
use Omadonex\LaravelTools\Support\Services\CommentService;

class CommentController extends Controller
{
    public function store(CommentRequest $request,  IAclService $aclService,  CommentService $commentService)
    {
        $data = $request->validated();
        $commentService->create($data + [
            'user_id' => $aclService->id(),
        ]);

        return redirect($request->redirect_url);
    }
}
