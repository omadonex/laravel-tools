<th style="{{ $style ?? '' }}">
    @include('omx-form::bs52.partials.select', [
        'cmpId'    => "{$tableId}__{$name}",
        'cmpName'  => "{$tableId}__filter_{$name}",
        'cmpList'  => $list,
        'cmpClass' => 'filter-input form-control-sm',
        'cmpValue' => data_get($filter, [$tableId, $name]) ?? $value ?? null,
        'cmpNoLabel' => true,
    ])
</th>
