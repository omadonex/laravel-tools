@php
    /** @var string|null $tableId */

    $modalId = !empty($tableId) ? "{$tableId}_ModalConfirmDelete" : 'PartialsModalConfirmDelete';
    $modalSubmitText = 'Удалить';
    $modalSubmitContext = \Omadonex\LaravelTools\Support\Tools\Context::DANGER;
    $modalTitle = 'Удаление объекта';
@endphp

@extends('omx-bootstrap::modal.base')

@section("{$modalId}-bodyCaption")
    <div class="h4 text-muted">Вы подтверждаете удаление?</div>
@endsection
