@extends('omx-form::bs52.base')

@section("{$formId}-body")
    @include('omx-form::bs52.partials.file', [
        'cmpId' => "{$formId}__inpImportFile",
        'cmpName' => 'import_file',
        'cmpValidate' => 'required',
    ])

    <div class="col-12">
        @include('omx-bootstrap::buttons.standard.upload', ['btnEntityId' => $formId, 'btnSubmit' => true, 'btnClass' => 'mt-5'])
    </div>
@endsection
