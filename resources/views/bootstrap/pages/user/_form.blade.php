@php
    $minPassLength = config('app.minPassLength');
@endphp

@extends('omx-form::bs52.base')

@section("{$formId}-body")
    <div class="row">
        <div class="col-6">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpUsername", 'cmpName' => 'username', 'cmpValidate' => 'required'])
            </div>
        </div>
        <div class="col-6">
            <label for="{{ $formId }}__inpPhone" class="form-label">{{ __("validation.attributes.phone") }}</label>
            <div class="input-group mb-4">
                <span class="input-group-text">+7</span>
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpPhone", 'cmpName' => 'phone', 'cmpValidate' => 'nullable|phone', 'cmpNoLabel' => true])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpEmail", 'cmpName' => 'email', 'cmpValidate' => 'required|email'])
            </div>
        </div>
        <div class="col-6">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpDisplayName", 'cmpName' => 'display_name'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            @if (!isset($editMode) || !$editMode)
            <div class="mb-4">
                @include('omx-form::bs52.partials.password', ['cmpId' => "{$formId}__inpPassword", 'cmpName' => 'password', 'cmpValidate' => "required|min:{$minPassLength}|confirmed"])
            </div>
            @endif
        </div>
        <div class="col-6">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpFirstName", 'cmpName' => 'first_name'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            @if (!isset($editMode) || !$editMode)
            <div class="mb-4">
                @include('omx-form::bs52.partials.password', ['cmpId' => "{$formId}__inpPasswordConfirm", 'cmpName' => 'password_confirmation', 'cmpValidate' => "required|min:{$minPassLength}"])
            </div>
            @endif
        </div>
        <div class="col-6">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpLastName", 'cmpName' => 'last_name'])
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-6"></div>
        <div class="col-6">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpOptName", 'cmpName' => 'opt_name'])
            </div>
        </div>
    </div>

    <div class="col-12">
        @include('omx-form::bs52.partials.submit', ['btnText' => ''])
    </div>
@endsection
