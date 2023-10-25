<?php

namespace Omadonex\LaravelTools\Support\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Omadonex\LaravelTools\Support\Models\ColumnSet;

class ColumnSetResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        /** @var ColumnSet $this */

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'user_id'  => $this->user_id,
            'page_id'  => $this->page_id,
            'table_id' => $this->table_id,
            'columns'  => $this->columns,
        ];
    }
}
