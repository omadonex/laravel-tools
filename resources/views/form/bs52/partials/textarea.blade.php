@if (!isset($noLabel) || !$noLabel)
    <label for="{{ $id }}" class="form-label">@include('omx-form::bs52.partials.required'){{ $label }}</label>
@endif
<textarea id="{{ $id }}" name="{{ $name }}"
          class="form-control {{ $class ?? '' }} {{ count($errors->get($name)) ? 'is-invalid' : '' }}"
          @if (isset($readonly) && $readonly) readonly="readonly" @endif
          @if (!isset($noPlaceholder) || !$noPlaceholder) placeholder="{{ $placeholder }}" @endif
          @if (isset($disabled) && $disabled) disabled @endif
          rows="{{ $rows ?? 4 }}"
          data-jst-field="{{ $name }}" @isset($noValidate) data-jst-no-validate="true" @else @isset($validate) data-jst-validate="{{ $validate }}" @endisset @endisset>
    @if (old($name)) {{ old($name) }} @elseif(isset($value)) {{ $value }} @endif
</textarea>
<div data-jst-field="{{ $name }}" class="invalid-feedback">{{ $errors->first($name) }}</div>