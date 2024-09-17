@php
    /** @var string $tableId  */

    $modalId    = "{$tableId}__modalCreateSettingColumns";
    $modalTitle = 'Создание набора колонок';
    $modalSubmitText = 'Создать';
    $modalSubmitIconHtml = getIconHtml('streamline.bold.add-bold', 14, 'currentColor', 'currentColor');

    $prefix = app('acl')->checkRole(\Omadonex\LaravelTools\Acl\Interfaces\IRole::ADMIN) ? 'admin.' : '';
    $routeName = "{$prefix}setting.column-set.store";
@endphp

@extends('omx-bootstrap::modal.base')

@section("{$modalId}-body")
    @include('omx-form::bs52.column-set._form', ['formId' => "{$tableId}__formCreateSettingColumns", 'method' => 'POST', 'action' => route($routeName)])
@endsection
