<button data-jst-submit id="{{ $formId }}__btnSubmit" type="button" class="btn btn-primary mt-6 mb-2 @isset($submitClass) {{ $submitClass }} @endif">
    <span data-jst-spinner style="margin-top: 0.15rem;" id="{{ $formId }}__spnSubmitSpinner" class="spinner-grow spinner-grow-sm float-end d-none" role="status" aria-hidden="true"></span>
    {{ $text }}
</button>
