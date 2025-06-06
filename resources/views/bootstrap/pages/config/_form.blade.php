@extends('omx-form::bs52.base')

@section("{$formId}-body")
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpName", 'cmpName' => 'value', 'cmpValidate' => 'required'])
            </div>
        </div>
    </div>

    <div class="col-12">
        @include('omx-form::bs52.partials.submit', ['btnText' => ''])
    </div>
@endsection
