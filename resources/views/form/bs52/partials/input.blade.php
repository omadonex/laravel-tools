@include('omx-form::bs52.partials.blocks.label')
<input
    type="text"
    @include('omx-form::bs52.partials.blocks.info')
    @include('omx-form::bs52.partials.blocks.placeholder')
    @include('omx-form::bs52.partials.blocks.disabled')
    @include('omx-form::bs52.partials.blocks.readonly')
    @include('omx-form::bs52.partials.blocks.validate')
    @include('omx-form::bs52.partials.blocks.old')
    class="form-control {{ $cmpClass ?? '' }} {{ count($errors->get($cmpName)) ? 'is-invalid' : '' }}"
>
@include('omx-form::bs52.partials.blocks.errors')