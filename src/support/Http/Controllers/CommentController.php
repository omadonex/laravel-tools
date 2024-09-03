<?php

namespace Omadonex\LaravelTools\Support\Http\Controllers;


use Omadonex\LaravelTools\Acl\Interfaces\IAclService;
use Omadonex\LaravelTools\Common\Http\Controllers\Controller;
use Omadonex\LaravelTools\Support\Http\Requests\CommentSimpleRequest;
use Omadonex\LaravelTools\Support\Http\Requests\CommentRequest;
use Omadonex\LaravelTools\Support\Models\Comment;
use Omadonex\LaravelTools\Support\Services\CommentService;
use Omadonex\LaravelTools\Support\Traits\JsonResponseTrait;

class CommentController extends Controller
{
    use JsonResponseTrait;

    public function store(CommentRequest $request, IAclService $aclService, CommentService $commentService)
    {
        $data = $request->validated();
        $body = json_decode($data['body'], true);

        if (trim($body['text'])) {
            unset($data['body']);
            $commentService->create(
                $data + [
                    'user_id' => $aclService->id(),
                    'text' => $body['json'],
                ]
            );
        }

        return redirect($request->redirect_url);
    }

    public function update($id, CommentSimpleRequest $request, CommentService $commentService)
    {
        $data = $request->validated();
        $body = json_decode($data['body'], true);

        if (trim($body['text'])) {
            unset($data['body']);
            $commentService->update($id,
                $data + [
                    'text' => $body['json'],
                ]
            );
        }

        return redirect($request->redirect_url);
    }


    public function reply($id, CommentSimpleRequest $request, IAclService $aclService, CommentService $commentService)
    {
        $data = $request->validated();
        $body = json_decode($data['body'], true);

        if (trim($body['text'])) {
            unset($data['body']);
            $commentService->create(
                $data + [
                    'user_id' => $aclService->id(),
                    'text' => $body['json'],
                    'commentable_type' => Comment::class,
                    'commentable_id' => $id,
                ]
            );
        }

        return redirect($request->redirect_url);
    }

    public function destroy($id, IAclService $aclService, CommentService $commentService)
    {
        $commentService->delete($id);

        return $this->defaultJsonResponse();
    }
}
