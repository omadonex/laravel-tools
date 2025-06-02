@php
    $tableList = $options['tableList'] ?? [];
    $filteredTableList = array_values(array_filter($tableList, function ($item) {
        return ($item['main'] ?? false) === true;
    }));
    $mainTable = count($filteredTableList) == 1 ? $filteredTableList[0] : ($tableList[0] ?? false);
@endphp

@extends('layouts.app')

@section('app-content')
    <div class="row">
        <div class="col-xl-12 d-flex" style="padding-left: unset; padding-right: unset;">
            @if ($mainTable)
                @include('omx-bootstrap::table.template', [
                    'page' => $options['page'],
                    'table' => $mainTable,
                    'modalParams' => [],
                ])
            @else
                @yield('main-page-content')
            @endif
        </div>
    </div>
@endsection
