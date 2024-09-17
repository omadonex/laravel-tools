@php
    /** @var \Omadonex\LaravelTools\Support\ModelView\ModelView $view */
@endphp

@if ($view->hasPrependEmpty())
    <th></th>
@endif

@if ($view->hasActionsPre())
    <th></th>
@endif

@foreach($columns as $column)
    @if ($view->isFilterInput($column))
        @include('omx-bootstrap::table.filterInput',  ['name' => $column, 'style' => $columnsData[$column]['style'] ?? ''])
    @elseif ($view->isFilterSelect($column))
        @include('omx-bootstrap::table.filterSelect', ['name' => $view->getKeyColumn($column), 'list' => $view->filterCallbackList($column)(), 'style' => $columnsData[$column]['style'] ?? ''])
    @elseif ($view->isFilterNone($column))
        <th></th>
    @endif
@endforeach

@foreach($view->getSpecificColumns() as $columnSpecificData)
    <th></th>
@endforeach

@if ($view->hasActions())
    <th></th>
@endif
