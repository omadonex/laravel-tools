<div class="form-check">
    <input
        type="checkbox"
        @include('omx-form::bs52.partials.blocks.info')
        @include('omx-form::bs52.partials.blocks.validate')
        @include('omx-form::bs52.partials.blocks.checked')
        class="form-check-input {{ $cmpClass ?? '' }}"
    >
    @include('omx-form::bs52.partials.blocks.label', ['cmpCheck' => true])
</div>
