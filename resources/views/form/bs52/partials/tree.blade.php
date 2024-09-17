@include('omx-form::bs52.partials.blocks.label')
{!! $cmpTreeData['buttonsHtml'] !!}
<div
    @include('omx-form::bs52.partials.blocks.info')
    @include('omx-form::bs52.partials.blocks.validate')
    class="{{ $cmpClass ?? '' }}"
    style="{{ $cmpStyle ?? '' }}"
    data-jst-component="jstree"
    data-value="{{ $cmpValue ?? null }}"
>
    {!! $cmpTreeData['bodyHtml'] !!}
</div>
@include('omx-form::bs52.partials.blocks.errors')