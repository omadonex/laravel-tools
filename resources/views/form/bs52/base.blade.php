<form id="{{ $formId }}" method="{{ $method }}" action="{{ $action }}" @if(isset($enctype)) enctype="{{ $enctype }}" @endif class="needs-validation" novalidate>
    @csrf
    @include('omx-form::bs52.partials.alert')
    @yield("{$formId}-body")
</form>
