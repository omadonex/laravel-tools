@php
    /** @var array $options */
    $table = $options['tableList'][0];
    $page = $options['page'];

    $tableId = $table['id'];
    $tableTitle = $table['ext']['title'];
    $tablePath = $table['ext']['path'];
    $tableFormPath = $table['ext']['formPath'];
    $view = $table['ext']['view'];

    $pageId = $page['id'];
    $pageTab = $page['tab'];
    $model = $page['model'];
    $modelClass = get_class($model);
@endphp

@extends('layouts.app')

@section('app-content')
    <div class="row">
        <div class="col-xl-12 d-flex">
            <div class="card border-0 flex-fill w-100">
                <div class="card-header border-0 card-header-space-between">
                    <h2 class="card-header-title h4 text-uppercase">Карточка записи (ID: {{ $model->getKey() }}) <a href="{{ route("{$tablePath}.index") }}">Таблица</a></h2>
                    @yield('page-buttons')
                </div>

                <div>
                    <ul class="nav nav-tabs px-5" id="{{ $pageId }}__tabCard" role="tablist">
                        @include('omx-bootstrap::resource.tab._button.main')
                        @yield('show-tab-buttons')
                        @if (defined("{$modelClass}::HISTORY_ENABLED") ? $modelClass::HISTORY_ENABLED : false)
                            @include('omx-bootstrap::resource.tab._button.history')
                        @endif
                    </ul>
                    <div class="tab-content" id="{{ $pageId }}__tabCard__pane">
                        @include('omx-bootstrap::resource.tab.main')
                        @yield('show-tab-content')
                        @if (defined("{$modelClass}::HISTORY_ENABLED") ? $modelClass::HISTORY_ENABLED : false)
                            @include('omx-bootstrap::resource.tab.history')
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
