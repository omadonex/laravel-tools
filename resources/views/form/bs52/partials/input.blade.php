@if (!isset($noLabel) || !$noLabel)
    <label for="{{ $id }}" class="form-label">{{ $label }}</label>
@endif
<input id="{{ $id }}" name="{{ $name }}" class="form-control {{ count($errors->get($name)) ? 'is-invalid' : '' }}" placeholder="{{ $placeholder }}"
       @if (old($name)) value="{{ old($name) }}" @endif
       data-jst-field="{{ $name }}" @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>
