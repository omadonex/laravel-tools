@include('omx-form::bs52.partials.blocks.label')
<div
    @include('omx-form::bs52.partials.blocks.info')
    @include('omx-form::bs52.partials.blocks.validate')
    class="{{ $cmpClass ?? '' }}"
    style="{{ $cmpStyle ?? '' }}"
    data-jst-component="quill"
    data-value="{{ $cmpValue ?? null }}"
>
</div>
@include('omx-form::bs52.partials.blocks.errors')