<div class="form-check form-switch">
    <input
        type="checkbox"
        @include('omx-form::bs52.partials.blocks.info')
        @include('omx-form::bs52.partials.blocks.validate')
        @include('omx-form::bs52.partials.blocks.checked')
        class="{{ $cmpClass ?? '' }} form-check-input"
        role="switch"
    >
    <label class="form-check-label" for="{{ $cmpId }}">{{ __("validation.attributes.{$cmpName}") }}</label>
</div>