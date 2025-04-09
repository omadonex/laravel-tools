@php
    $spinner = 'spinner-grow';
    $spinnerStyle = 'margin-top: .05rem; margin-left: .5rem; margin-right: -.5rem;';
@endphp

@extends('omx-form::bs52.base')

@section("{$formId}-body")
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.select', [
                    'cmpId'       => "{$formId}__sltRoleId",
                    'cmpName'     => 'role_id',
                    'cmpValidate' => 'required',
                    'cmpList'     => $unusedRoleList,
                ])
            </div>
        </div>
    </div>

    <div class="col-12">
        @include('omx-form::bs52.partials.submit', ['btnText' => 'Добавить'])
    </div>
@endsection
