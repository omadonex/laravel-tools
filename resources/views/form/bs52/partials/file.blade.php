@include('omx-form::bs52.partials.blocks.label')
<input
    type="file"
    @include('omx-form::bs52.partials.blocks.info')
    @include('omx-form::bs52.partials.blocks.placeholder')
    @include('omx-form::bs52.partials.blocks.validate')
    class="form-control {{ $cmpClass ?? '' }} {{ count($errors->get($cmpName)) ? 'is-invalid' : '' }}"
>
@include('omx-form::bs52.partials.blocks.errors')