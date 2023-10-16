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
            'columns'  => $this->columns,
            'table_id' => $this->table_id,
        ];
    }
}
