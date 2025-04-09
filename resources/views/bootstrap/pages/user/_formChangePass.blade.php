@php
    $spinner = 'spinner-grow';
    $spinnerStyle = 'margin-top: .05rem; margin-left: .5rem; margin-right: -.5rem;';
    $minPassLength = config('app.minPassLength');
@endphp

@extends('omx-form::bs52.base')

@section("{$formId}-body")
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.password', ['cmpId' => "{$formId}__inpPassword", 'cmpName' => 'password', 'cmpValidate' => "required|min:{$minPassLength}|confirmed"])
            </div>
        </div>

        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.password', ['cmpId' => "{$formId}__inpPasswordConfirm", 'cmpName' => 'password_confirmation', 'cmpValidate' => "required|min:{$minPassLength}"])
            </div>
        </div>
    </div>

    <div class="col-12">
        @include('omx-form::bs52.partials.submit', ['btnText' => 'Сменить'])
    </div>
@endsection
