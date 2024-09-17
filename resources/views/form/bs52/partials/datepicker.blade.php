@include('omx-form::bs52.partials.blocks.label')
<div class="input-group has-validation" data-jst-component="flatpickr" id="{{ $cmpId }}" data-jst-field="{{ $cmpName }}">
    <input
        type="text"
        id="{{ $cmpId }}Input"
        class="form-control {{ $cmpClass ?? '' }} {{ count($errors->get($cmpName)) ? 'is-invalid' : '' }}"
        @include('omx-form::bs52.partials.blocks.placeholder')
        @include('omx-form::bs52.partials.blocks.disabled')
        @include('omx-form::bs52.partials.blocks.readonly')
        @include('omx-form::bs52.partials.blocks.validate')
        @include('omx-form::bs52.partials.blocks.old')
        data-input
    >
    <span class="input-group-text cursor-pointer" data-toggle>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="18" width="18"><g><path d="M21.5,3H18.75a.25.25,0,0,1-.25-.25V1a1,1,0,0,0-2,0V5.75a.75.75,0,0,1-1.5,0V3.5a.5.5,0,0,0-.5-.5H8.25A.25.25,0,0,1,8,2.75V1A1,1,0,0,0,6,1V5.75a.75.75,0,0,1-1.5,0V3.5A.5.5,0,0,0,4,3H2.5a2,2,0,0,0-2,2V22a2,2,0,0,0,2,2h19a2,2,0,0,0,2-2V5A2,2,0,0,0,21.5,3Zm0,18.5a.5.5,0,0,1-.5.5H3a.5.5,0,0,1-.5-.5V9.5A.5.5,0,0,1,3,9H21a.5.5,0,0,1,.5.5Z" style="fill: currentColor"/><path d="M9.65,11.15a1.51,1.51,0,0,0-1.59.18L6.38,12.68a1,1,0,1,0,1.24,1.56l.88-.7V19a1,1,0,0,0,2,0V12.5A1.5,1.5,0,0,0,9.65,11.15Z" style="fill: currentColor"/><path d="M16,11H13a1,1,0,0,0,0,2h2.21l-2.62,5.58a1,1,0,0,0,.49,1.33,1,1,0,0,0,1.33-.48l3-6.34A1.51,1.51,0,0,0,16,11Z" style="fill: currentColor"/></g></svg>
    </span>
    <span class="input-group-text cursor-pointer" data-clear>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="14" width="14"><path d="M.44,21.44a1.49,1.49,0,0,0,0,2.12,1.5,1.5,0,0,0,2.12,0l9.26-9.26a.25.25,0,0,1,.36,0l9.26,9.26a1.5,1.5,0,0,0,2.12,0,1.49,1.49,0,0,0,0-2.12L14.3,12.18a.25.25,0,0,1,0-.36l9.26-9.26A1.5,1.5,0,0,0,21.44.44L12.18,9.7a.25.25,0,0,1-.36,0L2.56.44A1.5,1.5,0,0,0,.44,2.56L9.7,11.82a.25.25,0,0,1,0,.36Z" style="fill: currentColor"/></svg>
    </span>
</div>
@include('omx-form::bs52.partials.blocks.errors')