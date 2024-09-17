@extends('omx-form::bs52.base')

@section("{$formId}-body")
    <span class="d-none">
        @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpPageId", 'cmpName' => 'page_id', 'cmpValue' => $pageId])
        @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpTableId", 'cmpName' => 'table_id', 'cmpValue' => $tableId])
        @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpBackTab", 'cmpName' => 'tab', 'cmpValue' => $backTab])
    </span>

    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.input', ['cmpId' => "{$formId}__inpName", 'cmpName' => 'name', 'cmpValidate' => 'required'])
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="mb-4">
                @include('omx-form::bs52.partials.select', [
                   'cmpId'           => "{$formId}__inpColumns",
                   'cmpName'         => "columns",
                   'cmpList'         => $view->getLabels(),
                   'cmpMultiple'     => true,
                   'cmpSearch'       => true,
                   'cmpPlaceholder'  => '',
                   'cmpHideSelected' => true,
                   'cmpValidate'     => 'required'
               ])
            </div>
        </div>
    </div>

    <div class="col-12">
        @include('omx-form::bs52.partials.submit', ['btnText' => ''])
    </div>
@endsection
