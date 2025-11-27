<div class="form-check" style="{{ $cmpStyle ?? '' }}" data-jst-field-box="{{ $cmpName }}">
    <input
        type="checkbox"
        @include('omx-form::bs52.partials.blocks.info')
        @include('omx-form::bs52.partials.blocks.validate')
        @include('omx-form::bs52.partials.blocks.checked')
        class="form-check-input {{ $cmpClass ?? '' }}"
    >
    @include('omx-form::bs52.partials.blocks.label', ['cmpCheck' => true])
</div>
