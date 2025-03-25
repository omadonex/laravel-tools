@php
    /** @var \Omadonex\LaravelTools\Support\ModelView\ModelView $view */
@endphp

@if ($view->hasPrependEmpty())
    <th></th>
@endif

@if ($view->hasActionsPre() && !in_array('actions', $view->getIgnoreList()))
    <th>{{ $view->getLabel('actions_pre') }}</th>
@endif

@foreach($columns as $column)
    <th style="white-space:nowrap !important; {{ $view->getStyle($column) }}">{!! $view->getLabel($column) !!}</th>
@endforeach

@foreach($view->getSpecificColumns() as $columnSpecificData)
    <th>{{ $columnSpecificData['caption'] }}</th>
@endforeach

@if ($view->hasActions() && !in_array('actions', $view->getIgnoreList()))
    <th style="width: 100px">{{ $view->getLabel('actions') }}</th>
@endif
