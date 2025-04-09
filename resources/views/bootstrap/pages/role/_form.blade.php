@extends('omx-form::bs52.base')

@section("{$formId}-body")
    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpName", 'cmpName' => 'name', 'cmpValidate' => 'required'])
            </div>
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpDescription", 'cmpName' => 'description', 'cmpValidate' => 'required'])
            </div>
        </div>
    </div>
    @include('omx-form::bs52.partials.checkbox', ['cmpId' => "{$formId}__chbIsStaff", 'cmpName' => 'is_staff', 'cmpLabel' => 'Администраторский персонал', 'cmpNoValidate' => true])
    @include('omx-form::bs52.partials.checkbox', ['cmpId' => "{$formId}__chbIsHidden", 'cmpName' => 'is_hidden', 'cmpLabel' => 'Скрытая роль', 'cmpNoValidate' => true])

    <div class="col-12">
        @include('omx-form::bs52.partials.submit', ['btnText' => ''])
    </div>
@endsection
