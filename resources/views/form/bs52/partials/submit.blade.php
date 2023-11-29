<button data-jst-submit id="{{ $formId }}__btnSubmit" type="button" @isset($style) style="{{ $style }}" @endisset class="btn btn-primary mt-6 mb-2 @isset($submitClass) {{ $submitClass }} @endisset">
    <span data-jst-spinner style="margin: 0.1em -.5em 0 1em" id="{{ $formId }}__spnSubmitSpinner" class="spinner-grow spinner-grow-sm float-end d-none" role="status" aria-hidden="true"></span>
    @isset($iconHtml)
        <span style="margin-right: .5em; vertical-align: text-bottom;">{!! $iconHtml !!}</span>
    @endisset
    <span>{{ $text }}</span>
</button>
