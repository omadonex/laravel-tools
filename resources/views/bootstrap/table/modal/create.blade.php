@php
    /** @var string $tableId */
    /** @var string $tablePath */
    /** @var array $modalCaptions */
    /** @var array $modalParams */
    /** @var int $modalWidth */

    $modalId = "{$tableId}__modalCreate";
    $formId = "{$tableId}__formCreate";

    $modalTitle = $modalCaptions['create'];
    $modalSubmitText = 'Создать';
    $modalSubmitIconHtml = getIconHtml('streamline.bold.add-bold', 14, 'currentColor', 'currentColor');
@endphp

@extends('omx-bootstrap::modal.base')

@section("{$modalId}-body")
    @include("{$tablePath}._form", array_merge($modalParams, ['formId' => $formId, 'method' => 'POST', 'action' => route("{$tablePath}.store")]))
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
