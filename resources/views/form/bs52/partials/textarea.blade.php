@include('omx-form::bs52.partials.blocks.label')
<textarea
    @include('omx-form::bs52.partials.blocks.info')
    @include('omx-form::bs52.partials.blocks.placeholder')
    @include('omx-form::bs52.partials.blocks.readonly')
    @include('omx-form::bs52.partials.blocks.disabled')
    @include('omx-form::bs52.partials.blocks.validate')
    class="form-control {{ $cmpClass ?? '' }} {{ count($errors->get($cmpName)) ? 'is-invalid' : '' }}"
    rows="{{ $rows ?? 4 }}"
>
    @if (old($cmpName)) {{ old($cmpName) }} @elseif(isset($cmpValue)) {{ $cmpValue }} @endif
</textarea>
@include('omx-form::bs52.partials.blocks.errors')