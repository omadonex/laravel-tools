<th style="{{ $style ?? '' }}">
    <input
        class="form-control form-control-sm filter-input"
        name="{{ $tableId }}__filter_{{ $name }}"
        value="{{ data_get($filter, [$tableId, $name]) }}"
    >
</th>
