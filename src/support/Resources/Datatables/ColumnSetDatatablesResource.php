<?php

namespace Omadonex\LaravelTools\Support\Resources\Datatables;

use Omadonex\LaravelTools\Support\Classes\Utils\UtilsUserLabel;
use Omadonex\LaravelTools\Support\Resources\ColumnSetResource;

class ColumnSetDatatablesResource extends ColumnSetResource
{
    public function toArray($request): array
    {
        return array_merge(parent::toArray($request), [
            'user_id_label' => UtilsUserLabel::getFromResource($this, 'user_id'),
        ]);
    }
}
