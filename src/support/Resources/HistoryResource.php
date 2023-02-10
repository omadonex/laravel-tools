<?php

namespace Omadonex\LaravelTools\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id'   => $this->id,
            'model_id' => $this->model_id,
            'user_id' => $this->user_id,
            'history_event_id' => $this->history_event_id,
            'occur_at' => $this->occur_at,
            'data' => $this->data,
            'event' => $this->event,
        ];
    }
}
