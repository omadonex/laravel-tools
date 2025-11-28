@php
    /** @var string $tableId */
    /** @var string $tablePath */
    /** @var string $tableFormPath */
    /** @var array $modalCaptions */
    /** @var array $modalParams */
    /** @var int $modalWidth */

    $modalId = "{$tableId}__modalEdit";
    $formId = "{$tableId}__formEdit";

    $modalTitle = $modalCaptions['edit'];
    $modalSubmitText = 'Сохранить';
    $modalSubmitIconHtml = getIconHtml('streamline.bold.floppy-disk', 14, 'currentColor', 'currentColor');
@endphp

@extends('omx-bootstrap::modal.base')

@section("{$modalId}-body")
    @include($tableFormEdit, array_merge($modalParams, ['editMode' => true, 'formId' => $formId, 'method' => 'PUT', 'action' => route($tablePathList['update'], '*')]))
@endsection

@if ($modalWidth)
    <style>
        #{{ $modalId }} {
        &.modal {
            --bs-modal-width: {{ $modalWidth }}px;
        }
        .modal-content {
            width: {{ $modalWidth }}px;
        }
        };
    </style>
@endif

