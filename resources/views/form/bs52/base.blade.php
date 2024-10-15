<form id="{{ $formId }}" method="{{ $method == 'GET' ? $method : 'POST' }}" action="{{ $action }}" @if(isset($enctype)) enctype="{{ $enctype }}" @endif class="needs-validation @isset($formClass) {{ $formClass }} @endisset" @isset($formStyle) style="{{ $formStyle }}" @endisset novalidate>
    @csrf
    @if (!in_array($method, ['GET', 'POST']))
        @method($method)
    @endif
    @include('omx-form::bs52.partials.alert')
    @yield("{$formId}-body")
</form>
