<input type="file" id="{{ $id }}" name="{{ $name }}"
       class="form-control {{ $class }} {{ count($errors->get($name)) ? 'is-invalid' : '' }}"
       @if (isset($placeholder)) placeholder="{{ $placeholder }}" @endif
       data-jst-field="{{ $name }}"
>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>