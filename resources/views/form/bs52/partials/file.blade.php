@if (!isset($noLabel) || !$noLabel)
       <label for="{{ $id }}" class="form-label">{{ $label }}</label>
@endif
<input type="file" id="{{ $id }}" name="{{ $name }}"
       class="form-control {{ $class }} {{ count($errors->get($name)) ? 'is-invalid' : '' }}"
       @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
       data-jst-field="{{ $name }}"
       @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset
>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>