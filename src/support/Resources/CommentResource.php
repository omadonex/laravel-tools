<?php

namespace Omadonex\LaravelTools\Support\Resources;

use Omadonex\LaravelTools\Support\Models\Comment;

class CommentResource extends OmxJsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var Comment $this */

        return [
            'id'               => $this->id,
            'commentable_type' => $this->commentable_type,
            'commentable_id'   => $this->commentable_id,
            'text'             => $this->text,
            'user_id'          => $this->user_id,
            'created_at'       => $this->created_at,
            'updated_at'       => $this->updated_at,
        ];
    }
}
