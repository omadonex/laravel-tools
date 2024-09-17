@php
    /** @var string $tableId */

    $modalId = "{$tableId}__modalEdit";
    $modalTitle = 'Редактирование набора колонок';
    $modalSubmitText = 'Сохранить';
    $modalSubmitIconHtml = getIconHtml('streamline.bold.floppy-disk', 14, 'currentColor', 'currentColor');
@endphp

@extends('omx-bootstrap::modal.base')

@section("{$modalId}-body")
    @include('admin.tableColumnSetting._form', ['formId' => "{$tableId}__formEdit", 'method' => 'PUT', 'action' => route('admin.tableColumnSetting.update', '*')])
@endsection
