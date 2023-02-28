@if (!isset($noLabel) || !$noLabel)
    <label for="{{ $id }}" class="form-label">@include('omx-form::bs52.partials.required'){{ $label }}</label>
@endif
<input type="text" id="{{ $id }}" name="{{ $name }}" class="form-control {{ $class ?? '' }} {{ count($errors->get($name)) ? 'is-invalid' : '' }}" placeholder="{{ $placeholder }}"
       @if (old($name)) value="{{ old($name) }}" @elseif(isset($value)) value="{{ $value }}" @endif
       data-jst-field="{{ $name }}" @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>