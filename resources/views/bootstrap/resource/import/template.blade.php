@php
    /** @var array $options */
    $table = $options['tableList'][0];
    $page = $options['page'];

    $tableId = $table['id'];
    $tableTitle = $table['ext']['title'];
    $tablePath = $table['ext']['path'];
    $tableFormPath = $table['ext']['formPath'];
    /** @var \Omadonex\LaravelTools\Support\ModelView\ModelView $view */
    $view = $table['ext']['view'];

    $pageId = $page['id'];
    $pageTab = $page['tab'];
    $model = $page['model'];
    $formId = "{$pageId}__formImport";
    $formImportTemplateId = "{$pageId}__formImportTemplate";

    $columnsImport = $view->getColumnsImport();
@endphp

@extends('layouts.app')

@section('app-content')
    <div class="row">
        <div class="col-xl-12 d-flex">
            <div class="card border-0 flex-fill w-100">
                <div class="card-header border-0 card-header-space-between">
                    <h2 class="card-header-title h4 text-uppercase">Импорт данных в <a href="{{ route("{$tablePath}.index") }}">таблицу</a> <i>"{{ $tableTitle }}"</i></h2>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-lg-4">
                            @if (count($errors) > 0)
                                <div class="error">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @include('omx-bootstrap::resource.import._form', ['formId' => $formId, 'method' => 'POST', 'enctype' => 'multipart/form-data', 'action' => route("{$tablePath}.importUpload")])
                        </div>

                        <div class="col-lg-8">
                            <div>Формат файла: <strong>.xls, .xlsx</strong> </div>
                            <div>Обязательные колонки:</div>
                            <ul>
                                @foreach($columnsImport as $columnName => $columnData)
                                    <li>

                                        <strong>{{ $columnName }}</strong>
                                        - {{ trans("validation.attributes.{$columnName}") }}
                                        [<i>{{ trans("validation.types.{$columnData['type']}") }}</i>]
                                        @if ($columnData['list'] ?? false)
                                            <br/>
                                            <div>Выбор из списка значений:</div>
                                            @php
                                                $list = $view->importCallbackList($columnName)();
                                                $listMapped = [];
                                                foreach ($list as $key => $value) {
                                                    $listMapped[] = "{$key} ($value)";
                                                }
                                                $listData = implode(' | ', $listMapped);
                                            @endphp
                                            <div>{!! $listData !!}</div>
                                        @endif
                                    </li>
                                @endforeach
                            </ul>
                            @include('omx-bootstrap::resource.import._formImportTemplate', ['formId' => $formImportTemplateId, 'method' => 'POST', 'action' => route("{$tablePath}.importTemplateDownload")])
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-12">
                            @php
                                $failures = session()->get('import_failures');
                            @endphp
                            @if ($failures)
                                <div>Ошибки импорта</div>
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Строка</th>
                                            <th>Колонка</th>
                                            <th>Значение</th>
                                            <th>Ошибка</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($failures as $failure)
                                            <tr>
                                                <td>{{ $failure->row() }}</td>
                                                <td>{{ $failure->attribute() }}</td>
                                                <td>{{ $failure->values()['__original'][$failure->attribute()] }}</td>
                                                <td>
                                                    @foreach ($failure->errors() as $error)
                                                        <div>{{ $error }}</div>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            @else
                                <div>Пример файла:</div>
                                <table class="table">
                                    <thead>
                                    <tr>
                                        @foreach($columnsImport as $columnName => $columnData)
                                            <th>{{ $columnName }}</th>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @for($i = 0; $i < 5; $i++)
                                            <tr>
                                                @foreach($columnsImport as $columnName => $columnData)
                                                    <td>{{ $view->getImportRandomValue($columnName) }}</td>
                                                @endforeach
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
