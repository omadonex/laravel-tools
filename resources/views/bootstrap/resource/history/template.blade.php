@php
    /** @var array $options */
    $table = $options['tableList'][0];
    $tableId = $table['id'];
    $tableTitle = $table['ext']['title'];
    $tablePath = $table['ext']['path'];
    $tableFormPath = $table['ext']['formPath'];
    $view = $table['ext']['view'];
@endphp

@extends('layouts.app')

@section('app-content')
    <div class="row">
        <div class="col-xl-12 d-flex">
            <div class="card border-0 flex-fill w-100">
                <div class="card-header border-0 card-header-space-between">
                    <h2 class="card-header-title h4 text-uppercase">История изменений <a href="{{ route("{$tablePath}.index") }}">таблицы</a> <i>"{{ $tableTitle }}"</i></h2>
                </div>

                @include('omx-bootstrap::table.history', ['tableId' => "{$tableId}History", 'tablePath' => "{$tablePath}.history"])
            </div>
        </div>
    </div>
@endsection
