@extends('omx-form::bs52.base')

@section("{$formId}-body")
    @include('omx-form::bs52.partials.hidden', [
        'cmpId' => "{$pageId}__hdnPageId",
        'cmpName' => 'pageId',
        'cmpValue' => $pageId,
    ])
    @include('omx-form::bs52.partials.hidden', [
        'cmpId' => "{$pageId}__hdnTableId",
        'cmpName' => 'tableId',
        'cmpValue' => $tableId,
    ])
    @foreach($tableParams as $param => $value)
        @php $upParam = ucfirst($param); @endphp
        @include('omx-form::bs52.partials.hidden', [
            'cmpId' => "{$pageId}__hdn{$upParam}",
            'cmpName' => $param,
            'cmpValue' => $value,
        ])
    @endforeach
@endsection
