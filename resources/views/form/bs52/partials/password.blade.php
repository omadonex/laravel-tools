@include('omx-form::bs52.partials.blocks.label')
<div class="input-group has-validation">
    <input
        type="password"
        @include('omx-form::bs52.partials.blocks.info')
        @include('omx-form::bs52.partials.blocks.placeholder')
        @include('omx-form::bs52.partials.blocks.validate')
        class="form-control"
        autocomplete="off"
        data-toggle-password-input
    >
    <button type="button" class="input-group-text px-4 text-secondary link-primary" data-toggle-password tabindex="-1"></button>
    @include('omx-form::bs52.partials.blocks.errors')
</div>
