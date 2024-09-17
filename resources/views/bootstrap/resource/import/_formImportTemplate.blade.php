@extends('omx-form::bs52.base')

@section("{$formId}-body")
    @include('omx-bootstrap::buttons.standard.download', ['btnEntityId' => $formId, 'btnText' => 'Скачать шаблон'])
@endsection
